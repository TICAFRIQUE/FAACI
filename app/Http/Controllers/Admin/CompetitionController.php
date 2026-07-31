<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CandidatureCompetition;
use App\Models\Competition;
use App\Notifications\StatutCandidatureCompetition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CompetitionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $statut = $request->input('statut', 'tous');
            $query  = Competition::select('competitions.*');

            if ($statut !== 'tous') {
                $query->where('statut', $statut);
            }

            return DataTables::of($query)
                ->addColumn('statut_badge', function (Competition $c) {
                    $badge = Competition::statutsBadge()[$c->statut] ?? 'secondary';
                    $lib   = Competition::statuts()[$c->statut] ?? $c->statut;

                    return "<span class=\"badge bg-{$badge}\">{$lib}</span>";
                })
                ->addColumn('budget_fmt', fn ($c) => $c->budget ? number_format($c->budget, 0, ',', ' ').' FCFA' : '—')
                ->addColumn('date_limite_fmt', fn ($c) => $c->date_limite_candidature?->translatedFormat('d M Y') ?? '—')
                ->addColumn('candidatures_count', fn ($c) => $c->candidatures()->count())
                ->addColumn('actions', fn ($c) => view('admin.competitions._actions', compact('c'))->render())
                ->rawColumns(['statut_badge', 'actions'])
                ->make(true);
        }

        $compteurs = [
            'tous'      => Competition::count(),
            'brouillon' => Competition::where('statut', 'brouillon')->count(),
            'ouverte'   => Competition::where('statut', 'ouverte')->count(),
            'cloturee'  => Competition::where('statut', 'cloturee')->count(),
            'terminee'  => Competition::where('statut', 'terminee')->count(),
        ];

        return view('admin.competitions.index', compact('compteurs'));
    }

    public function create(): View
    {
        return view('admin.competitions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'titre'                   => ['required', 'string', 'max:200'],
            'description'             => ['nullable', 'string', 'max:5000'],
            'budget'                  => ['nullable', 'numeric', 'min:0'],
            'date_limite_candidature' => ['nullable', 'date'],
            'date_pitch'              => ['nullable', 'date'],
            'statut'                  => ['required', 'in:brouillon,ouverte,cloturee,terminee'],
        ]);

        try {
            $data['cree_par'] = auth()->user()?->id;
            Competition::create($data);

            return redirect()->route('admin.competitions.index')
                ->with('status', 'Compétition créée avec succès.');
        } catch (\Throwable $e) {
            Log::error('Erreur création compétition : '.$e->getMessage());

            return back()->withInput()->with('error', 'Une erreur est survenue.');
        }
    }

    public function show(Competition $competition): View
    {
        $competition->load(['createur', 'candidatures.candidat']);

        return view('admin.competitions.show', compact('competition'));
    }

    public function edit(Competition $competition): View
    {
        return view('admin.competitions.edit', compact('competition'));
    }

    public function update(Request $request, Competition $competition): RedirectResponse
    {
        $data = $request->validate([
            'titre'                   => ['required', 'string', 'max:200'],
            'description'             => ['nullable', 'string', 'max:5000'],
            'budget'                  => ['nullable', 'numeric', 'min:0'],
            'date_limite_candidature' => ['nullable', 'date'],
            'date_pitch'              => ['nullable', 'date'],
            'statut'                  => ['required', 'in:brouillon,ouverte,cloturee,terminee'],
        ]);

        try {
            $competition->update($data);

            return redirect()->route('admin.competitions.show', $competition)
                ->with('status', 'Compétition mise à jour.');
        } catch (\Throwable $e) {
            Log::error('Erreur mise à jour compétition #'.$competition->id.' : '.$e->getMessage());

            return back()->withInput()->with('error', 'Une erreur est survenue.');
        }
    }

    public function destroy(Competition $competition): RedirectResponse
    {
        try {
            $competition->delete();

            return redirect()->route('admin.competitions.index')
                ->with('status', 'Compétition supprimée.');
        } catch (\Throwable $e) {
            Log::error('Erreur suppression compétition #'.$competition->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    public function candidatures(Request $request, Competition $competition)
    {
        if ($request->ajax()) {
            $query = CandidatureCompetition::with('candidat')
                ->where('competition_id', $competition->id)
                ->select('candidatures_competition.*');

            $statut = $request->input('statut', 'tous');
            if ($statut !== 'tous') {
                $query->where('statut', $statut);
            }

            return DataTables::of($query)
                ->addColumn('candidat_nom', fn ($c) => $c->candidat?->nom_complet ?? '—')
                ->addColumn('statut_badge', function (CandidatureCompetition $c) {
                    $badge = CandidatureCompetition::statutsBadge()[$c->statut] ?? 'secondary';
                    $lib   = CandidatureCompetition::statuts()[$c->statut] ?? $c->statut;

                    return "<span class=\"badge bg-{$badge}\">{$lib}</span>";
                })
                ->addColumn('actions', fn ($c) => view('admin.competitions._actions_candidature', ['c' => $c, 'competition' => $competition])->render())
                ->rawColumns(['statut_badge', 'actions'])
                ->make(true);
        }

        $compteurs = [
            'tous'         => $competition->candidatures()->count(),
            'en_attente'   => $competition->candidatures()->where('statut', 'en_attente')->count(),
            'selectionnee' => $competition->candidatures()->where('statut', 'selectionnee')->count(),
            'gagnante'     => $competition->candidatures()->where('statut', 'gagnante')->count(),
            'eliminee'     => $competition->candidatures()->where('statut', 'eliminee')->count(),
        ];

        return view('admin.competitions.candidatures', compact('competition', 'compteurs'));
    }

    public function majCandidature(Request $request, Competition $competition, CandidatureCompetition $candidature): RedirectResponse
    {
        abort_unless($candidature->competition_id === $competition->id, 404);

        $data = $request->validate([
            'statut'    => ['required', 'in:en_attente,selectionnee,eliminee,gagnante'],
            'note_jury' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $ancienStatut = $candidature->statut;
            $candidature->update($data);

            // Notifier le candidat uniquement si le statut a changé
            if ($ancienStatut !== $candidature->statut && $candidature->candidat) {
                $candidature->load('competition');
                $candidature->candidat->notify(new StatutCandidatureCompetition($candidature));
            }

            return back()->with('status', 'Candidature mise à jour.');
        } catch (\Throwable $e) {
            Log::error('Erreur maj candidature #'.$candidature->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }
}
