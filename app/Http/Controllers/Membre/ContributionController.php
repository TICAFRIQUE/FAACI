<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Http\Requests\Membre\ContributionRequest;
use App\Models\Contribution;
use App\Models\Projet;
use App\Models\User;
use App\Notifications\Admin\NouvellePromesseInvestissement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class ContributionController extends Controller
{
    /** Mes investissements */
    public function index(): View
    {
        $contributions = Contribution::with('projet')
            ->where('utilisateur_id', Auth::id())
            ->latest()
            ->get();

        return view('membre.contributions.index', compact('contributions'));
    }

    /** Créer une promesse de financement */
    public function store(ContributionRequest $request, Projet $projet): RedirectResponse
    {
        abort_unless($projet->statut === Projet::STATUT_EN_FINANCEMENT, 403);

        $existe = Contribution::where('projet_id', $projet->id)
            ->where('utilisateur_id', Auth::id())
            ->where('statut', '!=', Contribution::STATUT_PAYE)
            ->exists();

        if ($existe) {
            return back()->with('error', 'Vous avez déjà une promesse active sur ce projet.');
        }

        try {
            $contribution = Contribution::create([
                'projet_id'      => $projet->id,
                'utilisateur_id' => Auth::id(),
                'montant_promis' => $request->validated('montant_promis'),
                'note'           => $request->validated('note'),
                'statut'         => Contribution::STATUT_PROMESSE,
            ]);

            $contribution->load('projet');
            $admins = User::role(['admin', 'super_admin'])->get();
            Notification::send($admins, new NouvellePromesseInvestissement($contribution, Auth::user()));

            return redirect()->route('membre.projets.show', $projet)
                ->with('status', 'Votre promesse de financement a été enregistrée. L\'administration s\'occupera de l\'enregistrement du paiement.');
        } catch (\Throwable $e) {
            Log::error('Erreur création contribution projet #'.$projet->id.' : '.$e->getMessage());
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /** Annuler une promesse (statut promesse uniquement) */
    public function annuler(Contribution $contribution): RedirectResponse
    {
        abort_unless($contribution->utilisateur_id === Auth::id(), 403);
        abort_unless($contribution->statut === Contribution::STATUT_PROMESSE, 403);

        try {
            $contribution->delete();

            return redirect()->route('membre.contributions.index')
                ->with('status', 'Promesse annulée.');
        } catch (\Throwable $e) {
            Log::error('Erreur annulation contribution #'.$contribution->id.' : '.$e->getMessage());
            return back()->with('error', 'Une erreur est survenue.');
        }
    }
}
