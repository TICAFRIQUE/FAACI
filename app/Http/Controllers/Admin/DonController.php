<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Don;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class DonController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $statut = $request->input('statut', 'tous');

            $query = Don::with('donateur')->select('dons.*');

            if ($statut !== 'tous') {
                $query->where('statut', $statut);
            }

            return DataTables::of($query)
                ->filterColumn('donateur', fn ($q, $k) => $q->whereHas('donateur', fn ($s) => $s->where('nom', 'like', "%{$k}%")->orWhere('prenom', 'like', "%{$k}%")))
                ->addColumn('donateur', fn ($d) => $d->donateur?->nom_complet ?? '—')
                ->addColumn('nature_libelle', fn ($d) => Don::natures()[$d->nature] ?? $d->nature)
                ->addColumn('montant_fmt', function ($d) {
                    if ($d->nature === 'argent') {
                        return number_format($d->montant, 0, ',', ' ').' FCFA';
                    }

                    return $d->valeur_estimee ?: '—';
                })
                ->addColumn('statut_badge', function ($d) {
                    $map = [
                        'en_attente' => ['warning', 'En attente'],
                        'confirme'   => ['success', 'Confirmé'],
                        'rejete'     => ['danger', 'Rejeté'],
                        'annule'     => ['secondary', 'Annulé'],
                    ];
                    [$col, $lib] = $map[$d->statut] ?? ['secondary', $d->statut];

                    return "<span class=\"badge bg-{$col}\">{$lib}</span>";
                })
                ->addColumn('actions', fn ($d) => view('admin.dons._actions', compact('d'))->render())
                ->rawColumns(['statut_badge', 'actions'])
                ->make(true);
        }

        $compteurs = [
            'tous'      => Don::count(),
            'en_attente'=> Don::where('statut', 'en_attente')->count(),
            'confirme'  => Don::where('statut', 'confirme')->count(),
            'rejete'    => Don::where('statut', 'rejete')->count(),
        ];

        return view('admin.dons.index', compact('compteurs'));
    }

    public function show(Don $don): View
    {
        $don->load(['donateur', 'validateur', 'media']);

        return view('admin.dons.show', compact('don'));
    }

    public function confirmer(Don $don): RedirectResponse
    {
        abort_unless($don->statut === Don::STATUT_EN_ATTENTE, 403);

        try {
            $don->update([
                'statut'          => Don::STATUT_CONFIRME,
                'valide_par'      => auth()->id(),
                'date_validation' => now(),
                'motif_rejet'     => null,
            ]);

            return back()->with('status', 'Don confirmé avec succès.');
        } catch (\Throwable $e) {
            Log::error('Erreur confirmation don #'.$don->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    public function rejeter(Request $request, Don $don): RedirectResponse
    {
        $request->validate(['motif_rejet' => ['required', 'string', 'max:500']]);
        abort_unless($don->statut === Don::STATUT_EN_ATTENTE, 403);

        try {
            $don->update([
                'statut'      => Don::STATUT_REJETE,
                'motif_rejet' => $request->motif_rejet,
                'valide_par'  => auth()->id(),
            ]);

            return back()->with('status', 'Don rejeté.');
        } catch (\Throwable $e) {
            Log::error('Erreur rejet don #'.$don->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }
}
