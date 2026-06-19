<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\OffreEmploi;
use App\Models\Projet;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $membre = auth()->user();

        $stats = [
            'membres_actifs'    => User::role('membre')->where('statut', User::STATUT_ACTIF)->count(),
            'projets_actifs'    => Projet::where('statut', Projet::STATUT_EN_FINANCEMENT)->count(),
            'mes_contributions' => $membre->contributions()->whereNotIn('statut', ['cancelled'])->count(),
            'mes_projets'       => $membre->projets()->count(),
        ];

        // Prochains événements (publiés, à venir)
        $prochains_evenements = Evenement::where('statut', Evenement::STATUT_PUBLIE)
            ->where('date_debut', '>=', now())
            ->orderBy('date_debut')
            ->take(3)
            ->get();

        // Dernières offres d'emploi actives
        $dernieres_offres = OffreEmploi::where('statut', OffreEmploi::STATUT_ACTIVE)
            ->latest()
            ->take(3)
            ->get();

        return view('membre.dashboard', compact('membre', 'stats', 'prochains_evenements', 'dernieres_offres'));
    }
}
