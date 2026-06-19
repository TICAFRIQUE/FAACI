<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Membre\OffreEmploiRequest;
use App\Models\Candidature;
use App\Models\OffreEmploi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class OffreEmploiController extends Controller
{
    public function index(Request $request): mixed
    {
        if ($request->ajax()) {
            $query = OffreEmploi::with('auteur')
                ->select('offres_emploi.*');

            if ($request->filled('statut') && $request->statut !== 'tous') {
                $query->where('statut', $request->statut);
            }

            return DataTables::of($query)
                ->addColumn('auteur_nom', fn ($o) => $o->auteur->nom_complet ?? '—')
                ->addColumn('type_badge', function ($o) {
                    $colors = ['cdi'=>'success','cdd'=>'primary','stage'=>'info','freelance'=>'warning','alternance'=>'secondary'];
                    $c = $colors[$o->type_contrat] ?? 'secondary';
                    return "<span class='badge bg-{$c}'>{$o->type_libelle}</span>";
                })
                ->addColumn('statut_badge', function ($o) {
                    $colors = ['en_attente'=>'warning','active'=>'success','brouillon'=>'secondary','expiree'=>'dark','rejetee'=>'danger'];
                    $c = $colors[$o->statut] ?? 'secondary';
                    return "<span class='badge bg-{$c}'>{$o->statut_libelle}</span>";
                })
                ->addColumn('nb_candidatures', fn ($o) => $o->candidatures()->count())
                ->addColumn('actions', function ($o) {
                    $show = route('admin.emplois.show', $o);
                    $html = "<a href='{$show}' class='btn btn-sm btn-outline-secondary'><i class='bi bi-eye'></i></a> ";
                    if ($o->statut === 'en_attente') {
                        $valider = route('admin.emplois.valider', $o);
                        $rejeter = route('admin.emplois.rejeter', $o);
                        $html .= "<button onclick=\"validerOffre('{$valider}')\" class='btn btn-sm btn-success'><i class='bi bi-check-lg'></i></button> ";
                        $html .= "<button onclick=\"rejeterOffre('{$rejeter}', '{$o->titre}')\" class='btn btn-sm btn-danger'><i class='bi bi-x-lg'></i></button>";
                    }
                    return $html;
                })
                ->rawColumns(['type_badge', 'statut_badge', 'actions'])
                ->make(true);
        }

        $compteurs = [
            'tous'       => OffreEmploi::count(),
            'en_attente' => OffreEmploi::where('statut', 'en_attente')->count(),
            'active'     => OffreEmploi::where('statut', 'active')->count(),
            'expiree'    => OffreEmploi::where('statut', 'expiree')->count(),
        ];

        return view('admin.emplois.index', compact('compteurs'));
    }

    public function create(): View
    {
        return view('admin.emplois.create');
    }

    public function store(OffreEmploiRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['utilisateur_id'] = auth()->id();
            $data['slug']           = OffreEmploi::uniqueSlug($data['titre']);
            $data['statut']         = OffreEmploi::STATUT_ACTIVE;
            $data['valide_par']     = auth()->id();
            $data['date_validation'] = now();

            OffreEmploi::create($data);

            return redirect()->route('admin.emplois.index')
                ->with('status', 'Offre d\'emploi créée et publiée.');
        } catch (\Throwable $e) {
            Log::error('Erreur création offre admin : '.$e->getMessage());
            return back()->withInput()->with('error', 'Une erreur est survenue.');
        }
    }

    public function show(OffreEmploi $offre): View
    {
        $offre->load(['auteur', 'validateur', 'candidatures.candidat']);

        return view('admin.emplois.show', compact('offre'));
    }

    public function valider(OffreEmploi $offre): RedirectResponse
    {
        try {
            abort_unless($offre->statut === OffreEmploi::STATUT_EN_ATTENTE, 422);

            $offre->update([
                'statut'          => OffreEmploi::STATUT_ACTIVE,
                'valide_par'      => auth()->id(),
                'date_validation' => now(),
                'motif_rejet'     => null,
            ]);

            return back()->with('status', 'Offre validée et publiée.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    public function rejeter(Request $request, OffreEmploi $offre): RedirectResponse
    {
        $request->validate(['motif_rejet' => 'required|string|max:500']);

        try {
            abort_unless($offre->statut === OffreEmploi::STATUT_EN_ATTENTE, 422);

            $offre->update([
                'statut'      => OffreEmploi::STATUT_REJETEE,
                'motif_rejet' => $request->motif_rejet,
            ]);

            return back()->with('status', 'Offre rejetée.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    public function candidatures(OffreEmploi $offre): View
    {
        $offre->load(['candidatures.candidat', 'candidatures.media']);

        return view('admin.emplois.candidatures', compact('offre'));
    }

    public function majCandidature(Request $request, Candidature $candidature): RedirectResponse
    {
        $request->validate([
            'statut'         => 'required|in:soumise,en_cours,acceptee,rejetee',
            'note_recruteur' => 'nullable|string|max:1000',
        ]);

        $candidature->update($request->only('statut', 'note_recruteur'));

        return back()->with('status', 'Statut de candidature mis à jour.');
    }
}
