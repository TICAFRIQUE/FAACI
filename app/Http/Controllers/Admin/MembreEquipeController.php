<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MembreEquipeRequest;
use App\Models\MembreEquipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MembreEquipeController extends Controller
{
    public function index(): View
    {
        $membres = MembreEquipe::orderBy('ordre')->get();

        return view('admin.equipe.index', compact('membres'));
    }

    public function create(): View
    {
        return view('admin.equipe.create');
    }

    public function store(MembreEquipeRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('photo');

        $membre = MembreEquipe::create([
            ...$data,
            'actif' => $request->boolean('actif'),
            'ordre' => (MembreEquipe::max('ordre') ?? 0) + 1,
        ]);

        if ($request->hasFile('photo')) {
            $membre->addMediaFromRequest('photo')->toMediaCollection('photo');
        }

        return redirect()->route('admin.equipe.index')->with('status', 'Membre ajouté avec succès.');
    }

    public function edit(MembreEquipe $equipe): View
    {
        return view('admin.equipe.edit', ['membre' => $equipe]);
    }

    public function update(MembreEquipeRequest $request, MembreEquipe $equipe): RedirectResponse
    {
        $data = $request->safe()->except('photo');

        $equipe->update([
            ...$data,
            'actif' => $request->boolean('actif'),
        ]);

        if ($request->hasFile('photo')) {
            $equipe->addMediaFromRequest('photo')->toMediaCollection('photo');
        }

        return redirect()->route('admin.equipe.index')->with('status', 'Membre mis à jour avec succès.');
    }

    public function destroy(MembreEquipe $equipe): RedirectResponse
    {
        $equipe->delete();

        return redirect()->route('admin.equipe.index')->with('status', 'Membre supprimé avec succès.');
    }

    public function basculer(MembreEquipe $equipe): RedirectResponse
    {
        $equipe->update(['actif' => ! $equipe->actif]);

        return redirect()->route('admin.equipe.index')->with('status', 'Statut mis à jour.');
    }

    public function deplacer(Request $request, MembreEquipe $equipe): RedirectResponse
    {
        $direction = $request->input('direction');

        $voisin = $direction === 'haut'
            ? MembreEquipe::where('ordre', '<', $equipe->ordre)->orderByDesc('ordre')->first()
            : MembreEquipe::where('ordre', '>', $equipe->ordre)->orderBy('ordre')->first();

        if ($voisin) {
            $ordreEquipe = $equipe->ordre;
            $equipe->update(['ordre' => $voisin->ordre]);
            $voisin->update(['ordre' => $ordreEquipe]);
        }

        return redirect()->route('admin.equipe.index');
    }
}
