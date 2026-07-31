<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypeCotisation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TypeCotisationController extends Controller
{
    public function index(): View
    {
        $types = TypeCotisation::withCount('periodes')->latest()->get();
        return view('admin.cotisations.types.index', compact('types'));
    }

    public function create(): View
    {
        return view('admin.cotisations.types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nom'              => ['required', 'string', 'max:200'],
            'frequence'        => ['required', 'in:' . implode(',', array_keys(TypeCotisation::frequences()))],
            'montant_standard' => ['required', 'numeric', 'min:0'],
            'description'      => ['nullable', 'string', 'max:500'],
            'date_debut'       => ['required', 'date'],
            'date_fin'         => ['nullable', 'date', 'after:date_debut'],
            'actif'            => ['boolean'],
        ]);
        $data['actif'] = $request->boolean('actif', true);

        try {
            TypeCotisation::create($data);
            return redirect()->route('admin.cotisations.types.index')
                ->with('status', 'Type de cotisation créé.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function edit(TypeCotisation $type): View
    {
        return view('admin.cotisations.types.edit', compact('type'));
    }

    public function update(Request $request, TypeCotisation $type): RedirectResponse
    {
        $data = $request->validate([
            'nom'              => ['required', 'string', 'max:200'],
            'frequence'        => ['required', 'in:' . implode(',', array_keys(TypeCotisation::frequences()))],
            'montant_standard' => ['required', 'numeric', 'min:0'],
            'description'      => ['nullable', 'string', 'max:500'],
            'date_debut'       => ['required', 'date'],
            'date_fin'         => ['nullable', 'date', 'after:date_debut'],
            'actif'            => ['boolean'],
        ]);
        $data['actif'] = $request->boolean('actif', true);

        try {
            $type->update($data);
            return redirect()->route('admin.cotisations.types.index')
                ->with('status', 'Type mis à jour.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function destroy(TypeCotisation $type): RedirectResponse
    {
        try {
            $type->delete();
            return redirect()->route('admin.cotisations.types.index')
                ->with('status', 'Type supprimé.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
}
