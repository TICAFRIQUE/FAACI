<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Http\Requests\Membre\ContributionRequest;
use App\Http\Requests\Membre\DeclarationPaiementRequest;
use App\Models\Contribution;
use App\Models\DeclarationPaiement;
use App\Models\Projet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ContributionController extends Controller
{
    /** Mes contributions */
    public function index(): View
    {
        $contributions = Contribution::with('projet')
            ->where('utilisateur_id', auth()->id())
            ->latest()
            ->get();

        return view('membre.contributions.index', compact('contributions'));
    }

    /** Créer une promesse de financement */
    public function store(ContributionRequest $request, Projet $projet): RedirectResponse
    {
        abort_unless($projet->statut === Projet::STATUT_EN_FINANCEMENT, 403);

        // Un membre ne peut pas promettre deux fois sur le même projet
        $existe = Contribution::where('projet_id', $projet->id)
            ->where('utilisateur_id', auth()->id())
            ->whereNotIn('statut', [Contribution::STATUT_CANCELLED])
            ->exists();

        if ($existe) {
            return back()->with('error', 'Vous avez déjà une promesse active sur ce projet.');
        }

        try {
            Contribution::create([
                'projet_id'      => $projet->id,
                'utilisateur_id' => auth()->id(),
                'montant_promis' => $request->validated('montant_promis'),
                'note'           => $request->validated('note'),
                'statut'         => Contribution::STATUT_PENDING,
            ]);

            return redirect()->route('membre.projets.show', $projet)
                ->with('status', 'Votre promesse de financement a été enregistrée. Effectuez le paiement hors plateforme puis déclarez-le.');
        } catch (\Throwable $e) {
            Log::error('Erreur création contribution projet #'.$projet->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /** Formulaire déclaration paiement */
    public function declarerPaiementForm(Contribution $contribution): View
    {
        abort_unless($contribution->utilisateur_id === auth()->id(), 403);
        abort_unless(in_array($contribution->statut, [Contribution::STATUT_PENDING, Contribution::STATUT_PARTIAL]), 403);

        $contribution->load(['projet', 'declarations.media']);

        return view('membre.contributions.declarer-paiement', compact('contribution'));
    }

    /** Enregistrer la déclaration de paiement (cumulative — chaque appel crée un enregistrement) */
    public function declarerPaiement(DeclarationPaiementRequest $request, Contribution $contribution): RedirectResponse
    {
        abort_unless($contribution->utilisateur_id === auth()->id(), 403);
        abort_unless(in_array($contribution->statut, [Contribution::STATUT_PENDING, Contribution::STATUT_PARTIAL]), 403);

        try {
            $data        = $request->validated();
            $montantCeDeclare = (float) $data['montant_paye'];

            DB::transaction(function () use ($request, $contribution, $data, $montantCeDeclare) {
                // 1. Créer l'enregistrement de déclaration
                $declaration = DeclarationPaiement::create([
                    'contribution_id' => $contribution->id,
                    'montant'         => $montantCeDeclare,
                    'moyen_paiement'  => $data['moyen_paiement'],
                    'note'            => $data['note'] ?? null,
                ]);

                if ($request->hasFile('preuve')) {
                    $declaration->addMediaFromRequest('preuve')->toMediaCollection('preuves');
                }

                // 2. Recalculer le total cumulatif depuis toutes les déclarations
                $totalDeclare = DeclarationPaiement::where('contribution_id', $contribution->id)->sum('montant');

                // 3. Statut selon si le total couvre la promesse
                $nouveauStatut = $totalDeclare >= $contribution->montant_promis
                    ? Contribution::STATUT_CONFIRMED
                    : Contribution::STATUT_PARTIAL;

                $contribution->update([
                    'montant_paye'              => $totalDeclare,
                    'moyen_paiement'            => $data['moyen_paiement'],
                    'note'                      => $data['note'] ?? $contribution->note,
                    'statut'                    => $nouveauStatut,
                    'date_declaration_paiement' => now(),
                ]);
            });

            $contribution->refresh();
            $msg = sprintf(
                'Déclaration enregistrée : %s FCFA. Total payé : %s / %s FCFA promis.',
                number_format($montantCeDeclare, 0, ',', ' '),
                number_format($contribution->montant_paye, 0, ',', ' '),
                number_format($contribution->montant_promis, 0, ',', ' ')
            );

            return redirect()->route('membre.contributions.index')->with('status', $msg);
        } catch (\Throwable $e) {
            Log::error('Erreur déclaration paiement contribution #'.$contribution->id.' : '.$e->getMessage());

            return back()->withInput()->with('error', 'Une erreur est survenue.');
        }
    }

    /** Annuler une contribution (pending uniquement) */
    public function annuler(Contribution $contribution): RedirectResponse
    {
        abort_unless($contribution->utilisateur_id === auth()->id(), 403);
        abort_unless($contribution->statut === Contribution::STATUT_PENDING, 403);

        try {
            $contribution->update(['statut' => Contribution::STATUT_CANCELLED]);

            return redirect()->route('membre.contributions.index')
                ->with('status', 'Promesse annulée.');
        } catch (\Throwable $e) {
            Log::error('Erreur annulation contribution #'.$contribution->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }
}
