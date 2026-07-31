<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Http\Requests\Membre\EntrepriseRequest;
use App\Models\Entreprise;
use App\Models\User;
use App\Notifications\Admin\NouvelleEntrepriseSoumise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class EntrepriseController extends Controller
{
    public function index(): View
    {
        $query = Entreprise::with('proprietaire')
            ->where('statut', Entreprise::STATUT_ACTIF);

        if ($q = request('q')) {
            $query->where(function ($s) use ($q) {
                $s->where('nom', 'like', "%{$q}%")
                  ->orWhere('secteur', 'like', "%{$q}%")
                  ->orWhere('localisation', 'like', "%{$q}%");
            });
        }

        if ($secteur = request('secteur')) {
            $query->where('secteur', $secteur);
        }

        $entreprises = $query->orderBy('nom')->paginate(12)->withQueryString();

        $secteurs = Entreprise::where('statut', Entreprise::STATUT_ACTIF)
            ->whereNotNull('secteur')
            ->distinct()
            ->orderBy('secteur')
            ->pluck('secteur');

        return view('membre.entreprises.index', compact('entreprises', 'secteurs'));
    }

    public function mesEntreprises(): View
    {
        $entreprises = Entreprise::where('utilisateur_id', Auth::id())
            ->latest()
            ->get();

        return view('membre.entreprises.mes-entreprises', compact('entreprises'));
    }

    public function create(): View
    {
        $secteurs = config('secteurs');

        return view('membre.entreprises.create', compact('secteurs'));
    }

    public function store(EntrepriseRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            unset($data['logo']);
            $data['utilisateur_id'] = Auth::id();
            $data['statut']         = Entreprise::STATUT_EN_ATTENTE;

            $entreprise = Entreprise::create($data);

            if ($request->hasFile('logo')) {
                $entreprise->addMediaFromRequest('logo')->toMediaCollection('logos');
            }

            $admins = User::role(['admin', 'super_admin'])->get();
            Notification::send($admins, new NouvelleEntrepriseSoumise($entreprise, Auth::user()));

            return redirect()->route('membre.entreprises.mes-entreprises')
                ->with('status', 'Entreprise soumise avec succès. Elle sera visible après validation par l\'équipe.');
        } catch (\Throwable $e) {
            Log::error('Erreur création entreprise membre #'.Auth::id().' : '.$e->getMessage());

            return back()->withInput()->with('error', 'Une erreur est survenue.');
        }
    }

    public function show(Entreprise $entreprise): View
    {
        abort_unless($entreprise->statut === Entreprise::STATUT_ACTIF, 404);
        $entreprise->load('proprietaire');

        return view('membre.entreprises.show', compact('entreprise'));
    }

    public function edit(Entreprise $entreprise): View
    {
        abort_unless($entreprise->utilisateur_id === Auth::id(), 403);
        $secteurs = config('secteurs');

        return view('membre.entreprises.edit', compact('entreprise', 'secteurs'));
    }

    public function update(EntrepriseRequest $request, Entreprise $entreprise): RedirectResponse
    {
        abort_unless($entreprise->utilisateur_id === Auth::id(), 403);

        try {
            $data = $request->validated();
            unset($data['logo']);

            // Retour en attente si l'entreprise était active (re-validation requise)
            if ($entreprise->statut === Entreprise::STATUT_ACTIF) {
                $data['statut']         = Entreprise::STATUT_EN_ATTENTE;
                $data['valide_par']     = null;
                $data['date_validation']= null;
            }

            $entreprise->update($data);

            if ($request->hasFile('logo')) {
                $entreprise->clearMediaCollection('logos');
                $entreprise->addMediaFromRequest('logo')->toMediaCollection('logos');
            }

            if ($entreprise->wasChanged('statut') && $entreprise->statut === Entreprise::STATUT_EN_ATTENTE) {
                $admins = User::role(['admin', 'super_admin'])->get();
                Notification::send($admins, new NouvelleEntrepriseSoumise($entreprise, Auth::user(), modification: true));
            }

            return redirect()->route('membre.entreprises.mes-entreprises')
                ->with('status', 'Entreprise mise à jour. Une re-validation est requise.');
        } catch (\Throwable $e) {
            Log::error('Erreur mise à jour entreprise #'.$entreprise->id.' : '.$e->getMessage());

            return back()->withInput()->with('error', 'Une erreur est survenue.');
        }
    }
}
