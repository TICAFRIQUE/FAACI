<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class EntrepriseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $statut = $request->input('statut', 'tous');

            $query = Entreprise::with('proprietaire')->select('entreprises.*');

            if ($statut !== 'tous') {
                $query->where('statut', $statut);
            }

            return DataTables::of($query)
                ->filterColumn('proprietaire', fn ($q, $k) => $q->whereHas('proprietaire', fn ($s) => $s->where('nom', 'like', "%{$k}%")->orWhere('prenom', 'like', "%{$k}%")))
                ->addColumn('proprietaire', fn ($e) => $e->proprietaire?->nom_complet ?? '—')
                ->addColumn('statut_badge', function ($e) {
                    $map = [
                        'en_attente' => ['warning', 'En attente'],
                        'actif'      => ['success', 'Active'],
                        'rejete'     => ['danger', 'Rejetée'],
                        'inactif'    => ['secondary', 'Inactive'],
                    ];
                    [$col, $lib] = $map[$e->statut] ?? ['secondary', $e->statut];

                    return "<span class=\"badge bg-{$col}\">{$lib}</span>";
                })
                ->addColumn('actions', fn ($e) => view('admin.entreprises._actions', compact('e'))->render())
                ->rawColumns(['statut_badge', 'actions'])
                ->make(true);
        }

        $compteurs = [
            'tous'      => Entreprise::count(),
            'en_attente'=> Entreprise::where('statut', 'en_attente')->count(),
            'actif'     => Entreprise::where('statut', 'actif')->count(),
            'rejete'    => Entreprise::where('statut', 'rejete')->count(),
        ];

        return view('admin.entreprises.index', compact('compteurs'));
    }

    public function show(Entreprise $entreprise): View
    {
        $entreprise->load(['proprietaire', 'validateur', 'media']);

        return view('admin.entreprises.show', compact('entreprise'));
    }

    public function valider(Entreprise $entreprise): RedirectResponse
    {
        abort_unless($entreprise->statut === Entreprise::STATUT_EN_ATTENTE, 403);

        try {
            $entreprise->update([
                'statut'          => Entreprise::STATUT_ACTIF,
                'valide_par'      => auth()->id(),
                'date_validation' => now(),
                'motif_rejet'     => null,
            ]);

            return back()->with('status', 'Entreprise validée et publiée dans l\'annuaire.');
        } catch (\Throwable $e) {
            Log::error('Erreur validation entreprise #'.$entreprise->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    public function rejeter(Request $request, Entreprise $entreprise): RedirectResponse
    {
        $request->validate(['motif_rejet' => ['required', 'string', 'max:500']]);
        abort_unless($entreprise->statut === Entreprise::STATUT_EN_ATTENTE, 403);

        try {
            $entreprise->update([
                'statut'      => Entreprise::STATUT_REJETE,
                'motif_rejet' => $request->motif_rejet,
                'valide_par'  => auth()->id(),
            ]);

            return back()->with('status', 'Entreprise rejetée.');
        } catch (\Throwable $e) {
            Log::error('Erreur rejet entreprise #'.$entreprise->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    public function desactiver(Entreprise $entreprise): RedirectResponse
    {
        abort_unless($entreprise->statut === Entreprise::STATUT_ACTIF, 403);

        try {
            $entreprise->update(['statut' => Entreprise::STATUT_INACTIF]);

            return back()->with('status', 'Entreprise désactivée.');
        } catch (\Throwable $e) {
            Log::error('Erreur désactivation entreprise #'.$entreprise->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    public function reactiver(Entreprise $entreprise): RedirectResponse
    {
        abort_unless($entreprise->statut === Entreprise::STATUT_INACTIF, 403);

        try {
            $entreprise->update([
                'statut'          => Entreprise::STATUT_ACTIF,
                'valide_par'      => auth()->id(),
                'date_validation' => now(),
            ]);

            return back()->with('status', 'Entreprise réactivée.');
        } catch (\Throwable $e) {
            Log::error('Erreur réactivation entreprise #'.$entreprise->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }
}
