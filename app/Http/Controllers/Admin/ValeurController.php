<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ValeurRequest;
use App\Models\Valeur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ValeurController extends Controller
{
    public function index(): View
    {
        $valeurs = Valeur::orderBy('ordre')->get();

        return view('admin.valeurs.index', compact('valeurs'));
    }

    public function create(): View
    {
        return view('admin.valeurs.create');
    }

    public function store(ValeurRequest $request): RedirectResponse
    {
        Valeur::create([
            ...$request->validated(),
            'ordre' => (Valeur::max('ordre') ?? 0) + 1,
        ]);

        return redirect()->route('admin.valeurs.index')->with('status', 'Valeur créée avec succès.');
    }

    public function edit(Valeur $valeur): View
    {
        return view('admin.valeurs.edit', compact('valeur'));
    }

    public function update(ValeurRequest $request, Valeur $valeur): RedirectResponse
    {
        $valeur->update($request->validated());

        return redirect()->route('admin.valeurs.index')->with('status', 'Valeur mise à jour avec succès.');
    }

    public function destroy(Valeur $valeur): RedirectResponse
    {
        $valeur->delete();

        return redirect()->route('admin.valeurs.index')->with('status', 'Valeur supprimée avec succès.');
    }

    public function deplacer(Request $request, Valeur $valeur): RedirectResponse
    {
        $direction = $request->input('direction');

        $voisin = $direction === 'haut'
            ? Valeur::where('ordre', '<', $valeur->ordre)->orderByDesc('ordre')->first()
            : Valeur::where('ordre', '>', $valeur->ordre)->orderBy('ordre')->first();

        if ($voisin) {
            $ordreValeur = $valeur->ordre;
            $valeur->update(['ordre' => $voisin->ordre]);
            $voisin->update(['ordre' => $ordreValeur]);
        }

        return redirect()->route('admin.valeurs.index');
    }
}
