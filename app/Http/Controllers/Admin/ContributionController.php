<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\DeclarationPaiement;
use App\Models\Projet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ContributionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $statut = $request->input('statut', 'tous');

            $query = Contribution::with(['projet', 'contributeur'])->select('contributions.*');

            if ($statut !== 'tous') {
                $query->where('statut', $statut);
            }

            return DataTables::of($query)
                ->filterColumn('contributeur', fn ($q, $k) => $q->whereHas('contributeur', fn ($s) => $s->where('nom', 'like', "%{$k}%")->orWhere('prenom', 'like', "%{$k}%")))
                ->filterColumn('projet', fn ($q, $k) => $q->whereHas('projet', fn ($s) => $s->where('titre', 'like', "%{$k}%")))
                ->addColumn('contributeur', fn ($c) => $c->contributeur?->nom_complet ?? '—')
                ->addColumn('projet', fn ($c) => $c->projet?->titre ?? '—')
                ->addColumn('montant_promis_fmt', fn ($c) => number_format($c->montant_promis, 0, ',', ' ').' FCFA')
                ->addColumn('montant_paye_fmt', fn ($c) => $c->montant_paye > 0 ? number_format($c->montant_paye, 0, ',', ' ').' FCFA' : '—')
                ->addColumn('moyen', fn ($c) => $c->moyen_paiement ? Contribution::moyensPaiement()[$c->moyen_paiement] : '—')
                ->addColumn('statut_badge', function ($c) {
                    $map = [
                        'pending'   => ['warning', 'En attente'],
                        'confirmed' => ['info', 'Déclaré'],
                        'paid'      => ['success', 'Validé'],
                        'partial'   => ['primary', 'Partiel'],
                        'cancelled' => ['secondary', 'Annulé'],
                    ];
                    [$col, $lib] = $map[$c->statut] ?? ['secondary', $c->statut];

                    return "<span class=\"badge bg-{$col}\">{$lib}</span>";
                })
                ->addColumn('actions', fn ($c) => view('admin.contributions._actions', compact('c'))->render())
                ->rawColumns(['statut_badge', 'actions'])
                ->make(true);
        }

        $compteurs = [
            'tous'      => Contribution::count(),
            'confirmed' => Contribution::where('statut', 'confirmed')->count(),
            'partial'   => Contribution::where('statut', 'partial')->count(),
            'paid'      => Contribution::where('statut', 'paid')->count(),
            'pending'   => Contribution::where('statut', 'pending')->count(),
        ];

        return view('admin.contributions.index', compact('compteurs'));
    }

    public function show(Contribution $contribution): View
    {
        $contribution->load(['projet', 'contributeur', 'validateur', 'declarations.validateur', 'declarations.media']);

        return view('admin.contributions.show', compact('contribution'));
    }

    /** Valider le paiement → paid + recalcul montant_collecte */
    public function valider(Contribution $contribution): RedirectResponse
    {
        abort_unless(in_array($contribution->statut, [Contribution::STATUT_CONFIRMED, Contribution::STATUT_PARTIAL]), 403);

        try {
            DB::transaction(function () use ($contribution) {
                $now = now();
                $adminId = auth()->id();

                // Marquer toutes les déclarations non encore validées comme validées
                DeclarationPaiement::where('contribution_id', $contribution->id)
                    ->whereNull('valide_par')
                    ->update([
                        'valide_par'      => $adminId,
                        'date_validation' => $now,
                    ]);

                $contribution->update([
                    'statut'          => Contribution::STATUT_PAID,
                    'valide_par'      => $adminId,
                    'date_validation' => $now,
                ]);

                // Recalcul du montant collecté sur le projet
                $totalPaye = Contribution::where('projet_id', $contribution->projet_id)
                    ->where('statut', Contribution::STATUT_PAID)
                    ->sum('montant_paye');

                $contribution->projet()->update(['montant_collecte' => $totalPaye]);
            });

            $contribution->refresh();
            return back()->with('status', sprintf(
                'Paiement validé : %s FCFA sur %s FCFA promis. Montant collecté du projet mis à jour.',
                number_format($contribution->montant_paye, 0, ',', ' '),
                number_format($contribution->montant_promis, 0, ',', ' ')
            ));
        } catch (\Throwable $e) {
            Log::error('Erreur validation contribution #'.$contribution->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /** Rejeter la déclaration → retour en pending */
    public function rejeter(Request $request, Contribution $contribution): RedirectResponse
    {
        $request->validate(['motif_rejet' => ['required', 'string', 'max:500']]);
        abort_unless(in_array($contribution->statut, [Contribution::STATUT_CONFIRMED, Contribution::STATUT_PARTIAL]), 403);

        try {
            $contribution->update([
                'statut'      => Contribution::STATUT_PENDING,
                'motif_rejet' => $request->motif_rejet,
            ]);

            return back()->with('status', 'Déclaration rejetée. Le membre devra re-déclarer son paiement.');
        } catch (\Throwable $e) {
            Log::error('Erreur rejet contribution #'.$contribution->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }
}
