<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CompteController extends Controller
{
    /**
     * Affiche le statut du compte de l'utilisateur connecté
     * (en attente, suspendu, inactif ou rejeté).
     */
    public function statut(): View
    {
        return view('compte.statut');
    }
}
