<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnuaireController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::role('membre')
            ->where('statut', User::STATUT_ACTIF)
            ->where('id', '!=', auth()->id());

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('prenom', 'like', "%{$q}%")
                    ->orWhere('nom', 'like', "%{$q}%")
                    ->orWhere('secteur', 'like', "%{$q}%")
                    ->orWhere('ville', 'like', "%{$q}%");
            });
        }

        if ($request->filled('secteur')) {
            $query->where('secteur', $request->input('secteur'));
        }

        if ($request->filled('ville')) {
            $query->where('ville', $request->input('ville'));
        }

        if ($request->filled('promotion')) {
            $query->where('promotion_aiesec', $request->input('promotion'));
        }

        $membres = $query->orderBy('nom')->orderBy('prenom')->paginate(20)->withQueryString();

        // Listes pour les filtres (depuis les fichiers de config)
        $secteurs   = config('secteurs');
        $villes     = array_keys(config('ville-commune'));
        $promotions = range((int) date('Y'), 2000);

        return view('membre.annuaire.index', compact('membres', 'secteurs', 'villes', 'promotions'));
    }

    public function show(User $membre): View
    {
        abort_if($membre->statut !== User::STATUT_ACTIF || ! $membre->hasRole('membre'), 404);

        return view('membre.annuaire.show', compact('membre'));
    }
}
