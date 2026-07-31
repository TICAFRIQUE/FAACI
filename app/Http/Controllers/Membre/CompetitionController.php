<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Models\CandidatureCompetition;
use App\Models\Competition;
use App\Models\User;
use App\Notifications\Admin\NouvelleCandidatureCompetition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class CompetitionController extends Controller
{
    public function index(): View
    {
        $competitions = Competition::where('statut', Competition::STATUT_OUVERTE)
            ->orderByDesc('created_at')
            ->paginate(9);

        $mesCandidatures = CandidatureCompetition::where('utilisateur_id', Auth::id())
            ->pluck('competition_id')
            ->toArray();

        return view('membre.competitions.index', compact('competitions', 'mesCandidatures'));
    }

    public function show(Competition $competition): View
    {
        abort_unless(
            in_array($competition->statut, [Competition::STATUT_OUVERTE, Competition::STATUT_CLOTUREE, Competition::STATUT_TERMINEE]),
            404
        );

        $maCandidature = CandidatureCompetition::where('competition_id', $competition->id)
            ->where('utilisateur_id', Auth::id())
            ->first();

        return view('membre.competitions.show', compact('competition', 'maCandidature'));
    }

    public function postuler(Request $request, Competition $competition): RedirectResponse
    {
        abort_unless($competition->estOuverte(), 403, 'Cette compétition n\'est plus ouverte aux candidatures.');

        $dejaCandidat = CandidatureCompetition::where('competition_id', $competition->id)
            ->where('utilisateur_id', Auth::id())
            ->exists();

        if ($dejaCandidat) {
            return back()->with('error', 'Vous avez déjà soumis une candidature pour cette compétition.');
        }

        $data = $request->validate([
            'titre_projet'  => ['required', 'string', 'max:200'],
            'resume_projet' => ['required', 'string', 'max:3000'],
        ]);

        try {
            $candidature = CandidatureCompetition::create([
                'competition_id'  => $competition->id,
                'utilisateur_id'  => Auth::id(),
                'titre_projet'    => $data['titre_projet'],
                'resume_projet'   => $data['resume_projet'],
                'statut'          => CandidatureCompetition::STATUT_EN_ATTENTE,
            ]);

            $candidature->load('competition');
            $admins = User::role(['admin', 'super_admin'])->get();
            Notification::send($admins, new NouvelleCandidatureCompetition($candidature, Auth::user()));

            return redirect()->route('membre.competitions.show', $competition)
                ->with('status', 'Votre candidature a été soumise avec succès !');
        } catch (\Throwable $e) {
            Log::error('Erreur soumission candidature compétition #'.$competition->id.' : '.$e->getMessage());

            return back()->withInput()->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    public function mesCandidatures(): View
    {
        $candidatures = CandidatureCompetition::with('competition')
            ->where('utilisateur_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('membre.competitions.mes-candidatures', compact('candidatures'));
    }
}
