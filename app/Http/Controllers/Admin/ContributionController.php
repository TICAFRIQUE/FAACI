<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\DeclarationPaiement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $query  = Contribution::with(['projet', 'contributeur'])->select('investissements.*');

            if ($statut !== 'tous') {
                $query->where('statut', $statut);
            }

            return DataTables::of($query)
                ->filterColumn('contributeur', fn ($q, $k) => $q->whereHas('contributeur', fn ($s) => $s->where('nom', 'like', "%{$k}%")->orWhere('prenom', 'like', "%{$k}%")))
                ->filterColumn('projet', fn ($q, $k) => $q->whereHas('projet', fn ($s) => $s->where('titre', 'like', "%{$k}%")))
                ->addColumn('contributeur',       fn ($c) => $c->contributeur?->nom_complet ?? '—')
                ->addColumn('projet',             fn ($c) => $c->projet?->titre ?? '—')
                ->addColumn('montant_promis_fmt', fn ($c) => number_format($c->montant_promis, 0, ',', ' ').' FCFA')
                ->addColumn('montant_paye_fmt',   fn ($c) => $c->montant_paye > 0 ? number_format($c->montant_paye, 0, ',', ' ').' FCFA' : '—')
                ->addColumn('moyen',              fn ($c) => $c->moyen_paiement ? Contribution::moyensPaiement()[$c->moyen_paiement] : '—')
                ->addColumn('statut_badge', function ($c) {
                    $col = Contribution::statutsBadge()[$c->statut] ?? 'secondary';
                    $lib = Contribution::statutsLibelles()[$c->statut] ?? $c->statut;
                    return "<span class=\"badge bg-{$col}\">{$lib}</span>";
                })
                ->addColumn('date_fmt', fn ($c) => $c->created_at->format('d/m/Y'))
                ->addColumn('actions', fn ($c) => view('admin.contributions._actions', compact('c'))->render())
                ->rawColumns(['statut_badge', 'actions'])
                ->make(true);
        }

        $compteurs = [
            'tous'     => Contribution::count(),
            'promesse' => Contribution::where('statut', Contribution::STATUT_PROMESSE)->count(),
            'partiel'  => Contribution::where('statut', Contribution::STATUT_PARTIEL)->count(),
            'paye'     => Contribution::where('statut', Contribution::STATUT_PAYE)->count(),
        ];

        return view('admin.contributions.index', compact('compteurs'));
    }

    public function show(Contribution $contribution): View
    {
        $contribution->load(['projet', 'contributeur', 'declarations.media']);

        return view('admin.contributions.show', compact('contribution'));
    }

    /** Admin enregistre un paiement (partiel ou total) directement — pas de validation séparée */
    public function enregistrerPaiement(Request $request, Contribution $contribution): RedirectResponse
    {
        abort_unless(in_array($contribution->statut, [Contribution::STATUT_PROMESSE, Contribution::STATUT_PARTIEL]), 403);

        $data = $request->validate([
            'montant'        => ['required', 'numeric', 'min:1'],
            'moyen_paiement' => ['required', 'in:' . implode(',', array_keys(Contribution::moyensPaiement()))],
            'note'           => ['nullable', 'string', 'max:500'],
            'preuve'         => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,webp,pdf'],
        ]);

        try {
            $montantCeDeclare = (float) $data['montant'];

            DB::transaction(function () use ($request, $contribution, $data, $montantCeDeclare) {
                $declaration = DeclarationPaiement::create([
                    'contribution_id' => $contribution->id,
                    'montant'         => $montantCeDeclare,
                    'moyen_paiement'  => $data['moyen_paiement'],
                    'note'            => $data['note'] ?? null,
                    'valide_par'      => Auth::id(),
                    'date_validation' => now(),
                ]);

                if ($request->hasFile('preuve')) {
                    $declaration->addMediaFromRequest('preuve')->toMediaCollection('preuves');
                }

                $totalPaye = DeclarationPaiement::where('contribution_id', $contribution->id)->sum('montant');

                $nouveauStatut = $totalPaye >= $contribution->montant_promis
                    ? Contribution::STATUT_PAYE
                    : Contribution::STATUT_PARTIEL;

                $contribution->update([
                    'montant_paye'              => $totalPaye,
                    'moyen_paiement'            => $data['moyen_paiement'],
                    'note'                      => $data['note'] ?? $contribution->note,
                    'statut'                    => $nouveauStatut,
                    'date_declaration_paiement' => now(),
                    'valide_par'                => Auth::id(),
                    'date_validation'           => now(),
                ]);

                // Recalcul montant collecté du projet
                if ($nouveauStatut === Contribution::STATUT_PAYE) {
                    $totalProjet = Contribution::where('projet_id', $contribution->projet_id)
                        ->where('statut', Contribution::STATUT_PAYE)
                        ->sum('montant_paye');
                    $contribution->projet()->update(['montant_collecte' => $totalProjet]);
                }
            });

            $contribution->refresh();

            return back()->with('status', sprintf(
                'Paiement de %s FCFA enregistré pour %s. Total payé : %s / %s FCFA.',
                number_format($montantCeDeclare, 0, ',', ' '),
                $contribution->contributeur?->nom_complet ?? 'ce membre',
                number_format($contribution->montant_paye, 0, ',', ' '),
                number_format($contribution->montant_promis, 0, ',', ' ')
            ));
        } catch (\Throwable $e) {
            Log::error('Erreur enregistrement paiement contribution #'.$contribution->id.' : '.$e->getMessage());
            return back()->withInput()->with('error', 'Une erreur est survenue.');
        }
    }
}
