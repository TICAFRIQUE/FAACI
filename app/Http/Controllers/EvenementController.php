<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\View\View;

class EvenementController extends Controller
{
    public function index(): View
    {
        $evenements = Evenement::where('statut', Evenement::STATUT_PUBLIE)
            ->where('est_public', true)
            ->orderBy('date_debut')
            ->paginate(9);

        return view('public.evenements.index', compact('evenements'));
    }

    public function show(Evenement $evenement): View
    {
        abort_unless($evenement->statut === Evenement::STATUT_PUBLIE && $evenement->est_public, 404);

        return view('public.evenements.show', compact('evenement'));
    }
}
