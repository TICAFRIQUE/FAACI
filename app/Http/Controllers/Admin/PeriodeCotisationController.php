<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeCotisation;
use App\Models\TypeCotisation;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeriodeCotisationController extends Controller
{
    public function index(): View
    {
        $types = TypeCotisation::actif()->with(['periodes' => fn($q) => $q->orderByDesc('date_debut')])->get();
        return view('admin.cotisations.periodes.index', compact('types'));
    }

    public function create(): View
    {
        $types = TypeCotisation::where('actif', true)->get();
        return view('admin.cotisations.periodes.create', compact('types'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type_cotisation_id'  => ['required', 'exists:types_cotisation,id'],
            'libelle'             => ['required', 'string', 'max:200'],
            'date_debut'          => ['required', 'date'],
            'date_fin_paiement'   => ['required', 'date', 'after_or_equal:date_debut'],
            'montant_standard'    => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $periode = PeriodeCotisation::create($request->only([
                'type_cotisation_id', 'libelle', 'date_debut', 'date_fin_paiement', 'montant_standard',
            ]));

            $nb = $periode->genererPourMembres();

            return redirect()->route('admin.cotisations.periodes.index')
                ->with('status', "Période créée — {$nb} ligne(s) générée(s) pour les membres actifs.");
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /** Génération en batch (N périodes d'un coup). */
    public function batch(Request $request): RedirectResponse
    {
        $request->validate([
            'type_cotisation_id' => ['required', 'exists:types_cotisation,id'],
            'date_debut'         => ['required', 'date'],
            'nb_periodes'        => ['required', 'integer', 'min:1', 'max:60'],
            'montant_standard'   => ['required', 'numeric', 'min:0'],
        ]);

        $type      = TypeCotisation::findOrFail($request->type_cotisation_id);
        $debut     = Carbon::parse($request->date_debut);
        $nb        = (int) $request->nb_periodes;
        $montant   = $request->montant_standard;
        $created   = 0;
        $membres   = 0;

        try {
            for ($i = 0; $i < $nb; $i++) {
                $fin = $this->calculerFin($debut, $type->frequence);

                $periode = PeriodeCotisation::create([
                    'type_cotisation_id' => $type->id,
                    'libelle'            => $this->libellePeriode($debut, $type->frequence),
                    'date_debut'         => $debut->toDateString(),
                    'date_fin_paiement'  => $fin->toDateString(),
                    'montant_standard'   => $montant,
                    'statut'             => PeriodeCotisation::STATUT_OUVERTE,
                ]);

                $membres += $periode->genererPourMembres();
                $debut    = $fin->addDay();
                $created++;
            }

            return redirect()->route('admin.cotisations.periodes.index')
                ->with('status', "{$created} période(s) générée(s) — {$membres} ligne(s) créées pour les membres.");
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function basculer(PeriodeCotisation $periode): RedirectResponse
    {
        $map = [
            PeriodeCotisation::STATUT_OUVERTE  => PeriodeCotisation::STATUT_FERMEE,
            PeriodeCotisation::STATUT_FERMEE   => PeriodeCotisation::STATUT_OUVERTE,
            PeriodeCotisation::STATUT_ARCHIVEE => PeriodeCotisation::STATUT_ARCHIVEE,
        ];

        try {
            // Marquer en retard les non-payés si on ferme
            if ($periode->statut === PeriodeCotisation::STATUT_OUVERTE) {
                $periode->cotisations()
                    ->whereIn('statut', ['en_attente', 'partiel'])
                    ->update(['statut' => 'en_retard']);
            }

            $periode->update(['statut' => $map[$periode->statut]]);

            return back()->with('status', 'Statut de la période mis à jour.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    private function calculerFin(Carbon $debut, string $frequence): Carbon
    {
        return match ($frequence) {
            'journaliere'  => $debut->copy(),
            'hebdomadaire' => $debut->copy()->addDays(6),
            'mensuelle'    => $debut->copy()->endOfMonth(),
            'semestrielle' => $debut->copy()->addMonths(6)->subDay(),
            'annuelle'     => $debut->copy()->addYear()->subDay(),
            default        => $debut->copy()->addMonth()->subDay(),
        };
    }

    private function libellePeriode(Carbon $debut, string $frequence): string
    {
        return match ($frequence) {
            'journaliere'  => $debut->translatedFormat('d F Y'),
            'hebdomadaire' => 'Semaine du ' . $debut->translatedFormat('d F Y'),
            'mensuelle'    => $debut->translatedFormat('F Y'),
            'semestrielle' => 'Semestre ' . ($debut->month <= 6 ? '1' : '2') . ' ' . $debut->year,
            'annuelle'     => 'Année ' . $debut->year,
            default        => $debut->translatedFormat('d F Y'),
        };
    }
}
