<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaiementCotisation;
use App\Models\TypeCotisation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CotisationAdminController extends Controller
{
    /** Liste globale des paiements déclarés (DataTable). */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $statut = $request->input('statut', 'tous');
            $query  = PaiementCotisation::with(['membre', 'type', 'saiseur'])->select('paiements_cotisation.*');

            if ($statut !== 'tous') {
                $query->where('statut', $statut);
            }

            return DataTables::of($query)
                ->addColumn('membre',       fn($p) => $p->membre?->nom_complet ?? '—')
                ->addColumn('type_nom',     fn($p) => $p->type?->nom ?? '—')
                ->addColumn('periodes_fmt', fn($p) => self::formatMoisCouverts($p->mois_couverts))
                ->addColumn('montant_fmt',  fn($p) => number_format($p->montant, 0, ',', ' ').' FCFA')
                ->addColumn('saisi_par_nom',fn($p) => $p->saiseur?->nom_complet ?? '—')
                ->addColumn('date_fmt',     fn($p) => $p->created_at->format('d/m/Y'))
                ->addColumn('statut_badge', function ($p) {
                    $map = ['en_attente'=>['warning','En attente'],'valide'=>['success','Validé'],'rejete'=>['danger','Rejeté']];
                    [$col,$lib] = $map[$p->statut] ?? ['secondary',$p->statut];
                    return "<span class='badge bg-{$col}'>{$lib}</span>";
                })
                ->addColumn('actions', fn($p) => view('admin.cotisations.paiements._actions', compact('p'))->render())
                ->rawColumns(['statut_badge','actions'])
                ->make(true);
        }

        $compteurs = [
            'tous'       => PaiementCotisation::count(),
            'en_attente' => PaiementCotisation::where('statut','en_attente')->count(),
            'valide'     => PaiementCotisation::where('statut','valide')->count(),
            'rejete'     => PaiementCotisation::where('statut','rejete')->count(),
        ];

        return view('admin.cotisations.paiements.index', compact('compteurs'));
    }

    public function show(PaiementCotisation $paiement): View
    {
        $paiement->load(['membre','type','validateur','saiseur','media']);
        return view('admin.cotisations.paiements.show', compact('paiement'));
    }

    public function valider(PaiementCotisation $paiement): RedirectResponse
    {
        abort_unless($paiement->statut === PaiementCotisation::STATUT_EN_ATTENTE, 403);
        try {
            $paiement->update([
                'statut'          => PaiementCotisation::STATUT_VALIDE,
                'valide_par'      => Auth::id(),
                'date_validation' => now(),
            ]);
            return back()->with('status', 'Paiement validé.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Erreur : '.$e->getMessage());
        }
    }

    public function rejeter(Request $request, PaiementCotisation $paiement): RedirectResponse
    {
        $request->validate(['motif_rejet' => ['required','string','max:500']]);
        abort_unless($paiement->statut === PaiementCotisation::STATUT_EN_ATTENTE, 403);
        try {
            $paiement->update([
                'statut'      => PaiementCotisation::STATUT_REJETE,
                'motif_rejet' => $request->motif_rejet,
                'valide_par'  => Auth::id(),
            ]);
            return back()->with('status', 'Paiement rejeté.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Erreur : '.$e->getMessage());
        }
    }

    /** Page calendrier + historique cotisations d'un membre. */
    public function membreCalendrier(User $membre): View
    {
        $types = TypeCotisation::where('actif', true)->whereNotNull('date_debut')->orderBy('nom')->get();

        $calendriers = [];
        foreach ($types as $type) {
            $calendriers[] = [
                'type'       => $type,
                'calendrier' => self::buildCalendrier($membre, $type),
            ];
        }

        // Mois déjà couverts (valide ou en_attente) par type → pour désactiver dans le picker
        $moisDejaCouverts = [];
        foreach ($calendriers as $bloc) {
            $tid  = $bloc['type']->id;
            $mois = [];
            foreach ($bloc['calendrier'] as $moisData) {
                foreach ($moisData as $cell) {
                    if (in_array($cell['statut'], ['valide', 'en_attente'])) {
                        $mois[] = $cell['mois'];
                    }
                }
            }
            $moisDejaCouverts[$tid] = $mois;
        }

        $paiements = PaiementCotisation::with('type')
            ->where('utilisateur_id', $membre->id)
            ->latest()
            ->get();

        return view('admin.cotisations.membre', compact('membre', 'types', 'calendriers', 'paiements', 'moisDejaCouverts'));
    }

    /** Enregistre et valide immédiatement un paiement pour un membre (depuis admin). */
    public function membrePaiement(Request $request, User $membre): RedirectResponse
    {
        $request->validate([
            'type_cotisation_id' => ['required','exists:types_cotisation,id'],
            'mois_couverts'      => ['required','array','min:1'],
            'mois_couverts.*'    => ['string','regex:/^\d{4}-\d{2}$/'],
            'montant'            => ['required','numeric','min:1'],
            'moyen_paiement'     => ['required','in:'.implode(',', array_keys(PaiementCotisation::moyensPaiement()))],
            'numero_transaction' => ['nullable','string','max:100'],
            'note'               => ['nullable','string','max:500'],
        ]);

        // Anti-doublon : rejeter les mois déjà couverts (valide ou en_attente)
        $moisDejaCouvert = PaiementCotisation::where('utilisateur_id', $membre->id)
            ->where('type_cotisation_id', $request->type_cotisation_id)
            ->whereIn('statut', [PaiementCotisation::STATUT_VALIDE, PaiementCotisation::STATUT_EN_ATTENTE])
            ->get()
            ->flatMap(fn($p) => $p->mois_couverts ?? [])
            ->unique()
            ->all();

        $doublons = array_intersect($request->mois_couverts, $moisDejaCouvert);
        if (!empty($doublons)) {
            $noms = array_map(
                fn($m) => \Carbon\Carbon::parse($m.'-01')->translatedFormat('M Y'),
                array_values($doublons)
            );
            return back()->withInput()
                ->with('error', 'Mois déjà couverts : '.implode(', ', $noms).'.');
        }

        try {
            $paiement = PaiementCotisation::create([
                'utilisateur_id'     => $membre->id,
                'type_cotisation_id' => $request->type_cotisation_id,
                'montant'            => $request->montant,
                'moyen_paiement'     => $request->moyen_paiement,
                'numero_transaction' => $request->numero_transaction,
                'mois_couverts'      => $request->mois_couverts,
                'note'               => $request->note,
                'saisi_par'          => Auth::id(),
                'statut'             => PaiementCotisation::STATUT_VALIDE,
                'valide_par'         => Auth::id(),
                'date_validation'    => now(),
            ]);

            if ($request->hasFile('preuve')) {
                $paiement->addMediaFromRequest('preuve')->toMediaCollection('preuves');
            }

            return redirect()->route('admin.cotisations.membre', $membre)
                ->with('status', 'Paiement enregistré pour '.$membre->nom_complet.'.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur : '.$e->getMessage());
        }
    }

    /**
     * Construit le calendrier Year × Month pour un membre et un type de cotisation.
     * Retourne un tableau indexé par année → mois (1-12) → ['mois' => 'YYYY-MM', 'statut' => ...]
     */
    public static function buildCalendrier(User $membre, TypeCotisation $type): array
    {
        if (!$type->date_debut) return [];

        $debut    = Carbon::parse($type->date_debut)->startOfMonth();
        $finType  = $type->date_fin ? Carbon::parse($type->date_fin)->endOfMonth() : null;
        $today    = Carbon::now()->startOfMonth();

        // Limite d'affichage par défaut : 6 mois dans le futur (ou date de fin si définie)
        $limiteAffichage = $finType ?? $today->copy()->addMonths(6);

        // Récupérer tous les paiements de ce membre pour ce type
        $paiements = PaiementCotisation::where('utilisateur_id', $membre->id)
            ->where('type_cotisation_id', $type->id)
            ->whereIn('statut', [PaiementCotisation::STATUT_VALIDE, PaiementCotisation::STATUT_EN_ATTENTE])
            ->get();

        // Étendre la limite d'affichage jusqu'au dernier mois payé (paiements anticipés)
        foreach ($paiements as $p) {
            foreach ($p->mois_couverts ?? [] as $mois) {
                $dateMois = Carbon::parse($mois . '-01')->endOfMonth();
                if ($dateMois > $limiteAffichage && (!$finType || $dateMois <= $finType)) {
                    $limiteAffichage = $dateMois;
                }
            }
        }

        // Construire la map mois → statut (le plus favorable gagne : valide > en_attente)
        $moisMap = []; // 'YYYY-MM' => 'valide' | 'en_attente'
        foreach ($paiements as $p) {
            foreach ($p->mois_couverts ?? [] as $mois) {
                if (!isset($moisMap[$mois]) || $p->statut === PaiementCotisation::STATUT_VALIDE) {
                    $moisMap[$mois] = $p->statut;
                }
            }
        }

        // Générer la grille
        $calendrier = [];
        $current    = $debut->copy();

        while ($current <= $limiteAffichage) {
            $annee  = $current->year;
            $moisN  = $current->month;
            $moisStr = $current->format('Y-m');

            if (isset($moisMap[$moisStr])) {
                $statut = $moisMap[$moisStr] === PaiementCotisation::STATUT_VALIDE ? 'valide' : 'en_attente';
            } elseif ($current > $today) {
                $statut = 'futur';   // bleu
            } else {
                $statut = 'retard';  // rouge
            }

            $calendrier[$annee][$moisN] = [
                'mois'   => $moisStr,
                'statut' => $statut,
            ];

            $current->addMonth();
        }

        return $calendrier;
    }

    /** Formate un tableau de mois ["2026-01","2026-02"] en texte lisible. */
    public static function formatMoisCouverts(?array $mois): string
    {
        if (!$mois) return '—';
        $noms = [];
        foreach ($mois as $m) {
            try {
                $noms[] = Carbon::parse($m.'-01')->translatedFormat('M Y');
            } catch (\Exception) {
                $noms[] = $m;
            }
        }
        return implode(', ', $noms);
    }
}
