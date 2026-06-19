<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActiviteRequest;
use App\Models\Activite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActiviteController extends Controller
{
    public function index(): View
    {
        $activites = Activite::orderBy('ordre')->get();

        return view('admin.activites.index', compact('activites'));
    }

    public function create(): View
    {
        return view('admin.activites.create');
    }

    public function store(ActiviteRequest $request): RedirectResponse
    {
        Activite::create([
            ...$request->validated(),
            'ordre' => (Activite::max('ordre') ?? 0) + 1,
            'actif' => $request->boolean('actif', true),
        ]);

        return redirect()->route('admin.activites.index')->with('status', 'Activité créée avec succès.');
    }

    public function edit(Activite $activite): View
    {
        return view('admin.activites.edit', compact('activite'));
    }

    public function update(ActiviteRequest $request, Activite $activite): RedirectResponse
    {
        $activite->update([
            ...$request->validated(),
            'actif' => $request->boolean('actif', true),
        ]);

        return redirect()->route('admin.activites.index')->with('status', 'Activité mise à jour avec succès.');
    }

    public function destroy(Activite $activite): RedirectResponse
    {
        $activite->delete();

        return redirect()->route('admin.activites.index')->with('status', 'Activité supprimée.');
    }

    public function basculer(Activite $activite): RedirectResponse
    {
        $activite->update(['actif' => ! $activite->actif]);

        return redirect()->route('admin.activites.index');
    }

    public function deplacer(Request $request, Activite $activite): RedirectResponse
    {
        $direction = $request->input('direction');

        $voisin = $direction === 'haut'
            ? Activite::where('ordre', '<', $activite->ordre)->orderByDesc('ordre')->first()
            : Activite::where('ordre', '>', $activite->ordre)->orderBy('ordre')->first();

        if ($voisin) {
            $ordreA = $activite->ordre;
            $activite->update(['ordre' => $voisin->ordre]);
            $voisin->update(['ordre' => $ordreA]);
        }

        return redirect()->route('admin.activites.index');
    }
}
