<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\MembreEquipe;
use App\Models\Projet;
use App\Models\Slide;
use App\Models\User;
use App\Models\Valeur;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // KPIs membres
        $membres = User::role('membre')->selectRaw("
            COUNT(*) as total,
            SUM(statut = 'actif') as actifs,
            SUM(statut = 'en_attente') as en_attente,
            SUM(statut = 'suspendu') as suspendus
        ")->first();

        // KPIs projets
        $projets = Projet::selectRaw("
            COUNT(*) as total,
            SUM(statut = 'en_attente') as en_attente,
            SUM(statut = 'en_financement') as en_financement,
            SUM(statut = 'finance') as finances,
            SUM(statut = 'en_cours') as en_cours
        ")->first();

        // KPIs contributions
        $contributions = Contribution::selectRaw("
            COUNT(*) as total,
            SUM(statut IN ('confirmed','partial')) as a_valider,
            SUM(statut = 'paid') as validees,
            SUM(CASE WHEN statut = 'paid' THEN montant_paye ELSE 0 END) as fonds_collectes
        ")->first();

        // Projets en attente (5 derniers)
        $projetsEnAttente = Projet::with('porteur')
            ->where('statut', 'en_attente')
            ->latest()
            ->limit(5)
            ->get();

        // Contributions à valider (5 dernières)
        $contribsAValider = Contribution::with(['contributeur', 'projet'])
            ->whereIn('statut', ['confirmed', 'partial'])
            ->latest()
            ->limit(5)
            ->get();

        // CMS stats
        $cms = [
            'slides'        => Slide::count(),
            'slides_actives'=> Slide::where('actif', true)->count(),
            'valeurs'       => Valeur::count(),
            'equipe'        => MembreEquipe::count(),
        ];

        return view('admin.dashboard', compact(
            'membres', 'projets', 'contributions',
            'projetsEnAttente', 'contribsAValider', 'cms'
        ));
    }
}
