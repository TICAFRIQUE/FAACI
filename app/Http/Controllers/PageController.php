<?php

namespace App\Http\Controllers;

use App\Models\Activite;
use App\Models\Article;
use App\Models\ContenuSection;
use App\Models\Evenement;
use App\Models\MembreEquipe;
use App\Models\Slide;
use App\Models\Valeur;
use Illuminate\View\View;

class PageController extends Controller
{
    public function accueil(): View
    {
        return view('public.accueil', [
            'slides'     => Slide::actives(),
            'about'      => ContenuSection::pourGroupe('accueil_about'),
            'valeurs'    => Valeur::toutes(),
            'stats'      => ContenuSection::pourGroupe('accueil_stats'),
            'activites'         => Activite::actives(),
            'activites_section' => ContenuSection::pourGroupe('accueil_activites'),
            'rejoindre'         => ContenuSection::pourGroupe('accueil_rejoindre'),
            'cta'        => ContenuSection::pourGroupe('accueil_cta'),
            'evenements' => Evenement::prochains(),
            'articles'   => Article::recents(),
        ]);
    }

    public function activites(): View
    {
        return view('public.activites', [
            'activites' => Activite::actives(),
            'contenus'  => ContenuSection::pourGroupe('activites_page'),
        ]);
    }

    public function mission(): View
    {
        return view('public.apropos.mission', [
            'contenus' => ContenuSection::pourGroupe('apropos_mission'),
        ]);
    }

    public function vision(): View
    {
        return view('public.apropos.vision', [
            'contenus' => ContenuSection::pourGroupe('apropos_vision'),
        ]);
    }

    public function valeurs(): View
    {
        return view('public.apropos.valeurs', [
            'valeurs' => Valeur::toutes(),
        ]);
    }

    public function equipe(): View
    {
        return view('public.apropos.equipe', [
            'membres' => MembreEquipe::actifs(),
        ]);
    }

    public function histoire(): View
    {
        return view('public.apropos.histoire', [
            'contenus' => ContenuSection::pourGroupe('apropos_histoire'),
        ]);
    }
}
