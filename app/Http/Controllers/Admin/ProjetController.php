<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\Projet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProjetController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $statut = $request->input('statut', 'tous');

            $query = Projet::with('porteur')->select('projets.*');

            if ($statut !== 'tous') {
                $query->where('statut', $statut);
            }

            return DataTables::of($query)
                ->filterColumn('titre', fn ($q, $k) => $q->where('projets.titre', 'like', "%{$k}%"))
                ->filterColumn('porteur', fn ($q, $k) => $q->whereHas('porteur', fn ($s) => $s->where('nom', 'like', "%{$k}%")->orWhere('prenom', 'like', "%{$k}%")))
                ->addColumn('porteur', fn ($p) => $p->porteur?->nom_complet ?? '—')
                ->addColumn('montant', fn ($p) => $p->montant_cible ? number_format($p->montant_cible, 0, ',', ' ').' FCFA' : 'Ouvert')
                ->addColumn('collecte', fn ($p) => number_format($p->montant_collecte, 0, ',', ' ').' FCFA')
                ->addColumn('statut_badge', function ($p) {
                    $map = [
                        'brouillon'      => ['secondary', 'Brouillon'],
                        'en_attente'     => ['warning', 'En attente'],
                        'valide'         => ['info', 'Validé'],
                        'en_financement' => ['primary', 'En financement'],
                        'finance'        => ['success', 'Financé'],
                        'en_cours'       => ['success', 'En cours'],
                        'termine'        => ['dark', 'Terminé'],
                        'rejete'         => ['danger', 'Rejeté'],
                    ];
                    [$c, $l] = $map[$p->statut] ?? ['secondary', $p->statut];

                    return "<span class=\"badge bg-{$c}\">{$l}</span>";
                })
                ->addColumn('actions', fn ($p) => view('admin.projets._actions', compact('p'))->render())
                ->rawColumns(['statut_badge', 'actions'])
                ->make(true);
        }

        $compteurs = Projet::selectRaw("
            COUNT(*) as tous,
            SUM(statut = 'en_attente') as en_attente,
            SUM(statut = 'valide') as valide,
            SUM(statut = 'en_financement') as en_financement,
            SUM(statut = 'finance') as finance,
            SUM(statut = 'en_cours') as en_cours,
            SUM(statut = 'termine') as termine,
            SUM(statut = 'rejete') as rejete
        ")->first()->toArray();

        return view('admin.projets.index', compact('compteurs'));
    }

    public function show(Projet $projet): View
    {
        $projet->load(['porteur', 'validateur', 'contributions.contributeur']);

        return view('admin.projets.show', compact('projet'));
    }

    /** Valider → en_financement */
    public function valider(Projet $projet): RedirectResponse
    {
        abort_unless(in_array($projet->statut, ['en_attente', 'valide']), 403);

        try {
            $projet->update([
                'statut'          => Projet::STATUT_EN_FINANCEMENT,
                'valide_par'      => Auth::id(),
                'date_validation' => now(),
                'motif_rejet'     => null,
            ]);

            return back()->with('status', 'Projet mis en financement.');
        } catch (\Throwable $e) {
            Log::error('Erreur validation projet #'.$projet->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /** Rejeter */
    public function rejeter(Request $request, Projet $projet): RedirectResponse
    {
        $request->validate(['motif_rejet' => ['required', 'string', 'max:1000']]);

        try {
            $projet->update([
                'statut'      => Projet::STATUT_REJETE,
                'motif_rejet' => $request->motif_rejet,
            ]);

            return back()->with('status', 'Projet rejeté.');
        } catch (\Throwable $e) {
            Log::error('Erreur rejet projet #'.$projet->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /** Changer le statut manuellement */
    public function changerStatut(Request $request, Projet $projet): RedirectResponse
    {
        $request->validate([
            'statut' => ['required', 'in:valide,en_financement,finance,en_cours,termine'],
        ]);

        try {
            $projet->update(['statut' => $request->statut]);

            return back()->with('status', 'Statut mis à jour.');
        } catch (\Throwable $e) {
            Log::error('Erreur changement statut projet #'.$projet->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /** Export PDF (page imprimable) des contributeurs d'un projet */
    public function exportPdf(Request $request, Projet $projet): View
    {
        $statut       = $request->input('statut', 'tous');
        $contributions = $projet->contributions()
            ->with('contributeur')
            ->when($statut !== 'tous', fn ($q) => $q->where('statut', $statut))
            ->orderBy('created_at')
            ->get();

        return view('admin.projets.export-contributeurs-pdf', compact('projet', 'contributions', 'statut'));
    }

    /** Export CSV des contributeurs d'un projet */
    public function exportCsv(Request $request, Projet $projet): StreamedResponse
    {
        $statut       = $request->input('statut', 'tous');
        $contributions = $projet->contributions()
            ->with('contributeur')
            ->when($statut !== 'tous', fn ($q) => $q->where('statut', $statut))
            ->orderBy('created_at')
            ->get();

        $slug     = str($projet->titre)->slug()->value();
        $filename = "contributeurs-{$slug}.csv";

        return response()->streamDownload(function () use ($contributions) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 pour Excel
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['#', 'Nom complet', 'Email', 'Montant promis (FCFA)', 'Montant payé (FCFA)', 'Moyen de paiement', 'Statut', 'Date promesse'], ';');

            $statutsLibelles  = Contribution::statutsLibelles();
            $moyensPaiement   = Contribution::moyensPaiement();
            $i = 1;

            foreach ($contributions as $c) {
                fputcsv($handle, [
                    $i++,
                    $c->contributeur?->nom_complet ?? '—',
                    $c->contributeur?->email ?? '—',
                    number_format((float) $c->montant_promis, 0, ',', ' '),
                    $c->montant_paye > 0 ? number_format((float) $c->montant_paye, 0, ',', ' ') : '0',
                    $moyensPaiement[$c->moyen_paiement] ?? '—',
                    $statutsLibelles[$c->statut] ?? $c->statut,
                    $c->created_at->format('d/m/Y'),
                ], ';');
            }
            fclose($handle);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
