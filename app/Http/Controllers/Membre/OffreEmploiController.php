<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Http\Requests\Membre\CandidatureRequest;
use App\Http\Requests\Membre\OffreEmploiRequest;
use App\Models\Candidature;
use App\Models\OffreEmploi;
use App\Models\User;
use App\Notifications\Admin\NouvelleCandidatureEmploi;
use App\Notifications\Admin\NouvelleOffreEmploiSoumise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class OffreEmploiController extends Controller
{
    /** Liste des offres actives */
    public function index(Request $request): View
    {
        $query = OffreEmploi::where('statut', OffreEmploi::STATUT_ACTIVE)
            ->with('auteur')
            ->latest();

        if ($request->filled('type')) {
            $query->where('type_contrat', $request->type);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('titre', 'like', "%{$q}%")
                    ->orWhere('localisation', 'like', "%{$q}%");
            });
        }

        $offres = $query->paginate(12)->withQueryString();

        // Mes candidatures pour savoir si j'ai déjà postulé
        $mesCandidatureIds = Candidature::where('utilisateur_id', Auth::id())
            ->whereIn('offre_emploi_id', $offres->pluck('id'))
            ->pluck('offre_emploi_id')
            ->toArray();

        return view('membre.emplois.index', compact('offres', 'mesCandidatureIds'));
    }

    /** Détail d'une offre */
    public function show(OffreEmploi $offre): View
    {
        abort_unless($offre->statut === OffreEmploi::STATUT_ACTIVE || $offre->utilisateur_id === Auth::id(), 404);

        $offre->load('auteur');

        $maCandidature = Candidature::where('offre_emploi_id', $offre->id)
            ->where('utilisateur_id', Auth::id())
            ->first();

        return view('membre.emplois.show', compact('offre', 'maCandidature'));
    }

    /** Mes offres publiées */
    public function mesOffres(): View
    {
        $offres = OffreEmploi::where('utilisateur_id', Auth::id())
            ->withCount('candidatures')
            ->latest()
            ->get();

        return view('membre.emplois.mes-offres', compact('offres'));
    }

    /** Formulaire création */
    public function create(): View
    {
        return view('membre.emplois.create');
    }

    /** Enregistrer une offre */
    public function store(OffreEmploiRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['utilisateur_id'] = Auth::id();
            $data['slug']           = OffreEmploi::uniqueSlug($data['titre']);
            $data['statut']         = OffreEmploi::STATUT_EN_ATTENTE;

            $offre = OffreEmploi::create($data);

            $admins = User::role(['admin', 'super_admin'])->get();
            Notification::send($admins, new NouvelleOffreEmploiSoumise($offre, Auth::user()));

            return redirect()->route('membre.emplois.mes-offres')
                ->with('status', 'Votre offre a été soumise. Elle sera publiée après validation admin.');
        } catch (\Throwable $e) {
            Log::error('Erreur création offre emploi : '.$e->getMessage());
            return back()->withInput()->with('error', 'Une erreur est survenue.');
        }
    }

    /** Formulaire édition (brouillon / en_attente du même auteur) */
    public function edit(OffreEmploi $offre): View
    {
        abort_unless($offre->utilisateur_id === Auth::id(), 403);
        abort_unless(in_array($offre->statut, [OffreEmploi::STATUT_EN_ATTENTE, OffreEmploi::STATUT_REJETEE]), 403);

        return view('membre.emplois.edit', compact('offre'));
    }

    /** Mettre à jour */
    public function update(OffreEmploiRequest $request, OffreEmploi $offre): RedirectResponse
    {
        abort_unless($offre->utilisateur_id === Auth::id(), 403);
        abort_unless(in_array($offre->statut, [OffreEmploi::STATUT_EN_ATTENTE, OffreEmploi::STATUT_REJETEE]), 403);

        try {
            $offre->update(array_merge($request->validated(), [
                'statut'      => OffreEmploi::STATUT_EN_ATTENTE,
                'motif_rejet' => null,
            ]));

            return redirect()->route('membre.emplois.mes-offres')
                ->with('status', 'Offre mise à jour et soumise à validation.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Une erreur est survenue.');
        }
    }

    /** Supprimer (en_attente ou rejetee uniquement) */
    public function destroy(OffreEmploi $offre): RedirectResponse
    {
        abort_unless($offre->utilisateur_id === Auth::id(), 403);
        abort_unless(in_array($offre->statut, [OffreEmploi::STATUT_EN_ATTENTE, OffreEmploi::STATUT_REJETEE]), 403);

        try {
            $offre->delete();
            return redirect()->route('membre.emplois.mes-offres')->with('status', 'Offre supprimée.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /** Postuler à une offre */
    public function postuler(CandidatureRequest $request, OffreEmploi $offre): RedirectResponse
    {
        abort_unless($offre->statut === OffreEmploi::STATUT_ACTIVE, 403);
        abort_unless($offre->utilisateur_id !== Auth::id(), 403, 'Vous ne pouvez pas postuler à votre propre offre.');

        $dejaPostule = Candidature::where('offre_emploi_id', $offre->id)
            ->where('utilisateur_id', Auth::id())
            ->exists();

        if ($dejaPostule) {
            return back()->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        try {
            $candidature = Candidature::create([
                'offre_emploi_id'   => $offre->id,
                'utilisateur_id'    => Auth::id(),
                'lettre_motivation' => $request->lettre_motivation,
                'statut'            => Candidature::STATUT_SOUMISE,
            ]);

            if ($request->hasFile('cv')) {
                $candidature->addMediaFromRequest('cv')->toMediaCollection('cv');
            }

            $candidature->load('offre');
            $admins = User::role(['admin', 'super_admin'])->get();
            Notification::send($admins, new NouvelleCandidatureEmploi($candidature, Auth::user()));

            return back()->with('status', 'Candidature envoyée avec succès !');
        } catch (\Throwable $e) {
            Log::error('Erreur candidature : '.$e->getMessage());
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /** Mes candidatures */
    public function mesCandidatures(): View
    {
        $candidatures = Candidature::where('utilisateur_id', Auth::id())
            ->with('offre')
            ->latest()
            ->get();

        return view('membre.emplois.mes-candidatures', compact('candidatures'));
    }
}
