<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnonceController extends Controller
{
    public function index(): View
    {
        $annonces = Annonce::with('auteur')->orderByDesc('created_at')->paginate(20);
        return view('admin.annonces.index', compact('annonces'));
    }

    public function create(): View
    {
        $types = Annonce::TYPES;
        return view('admin.annonces.create', compact('types'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->validate([
                'titre'     => ['required', 'string', 'max:255'],
                'contenu'   => ['required', 'string'],
                'type'      => ['required', 'in:' . implode(',', array_keys(Annonce::TYPES))],
                'statut'    => ['required', 'in:brouillon,publiee,archivee'],
                'expire_at' => ['nullable', 'date', 'after:now'],
            ]);

            if ($data['statut'] === Annonce::STATUT_PUBLIEE) {
                $data['publiee_at'] = now();
            }

            $data['auteur_id'] = auth()->id();

            Annonce::create($data);

            return redirect()->route('admin.annonces.index')->with('status', 'Annonce créée avec succès.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function edit(Annonce $annonce): View
    {
        $types = Annonce::TYPES;
        return view('admin.annonces.edit', compact('annonce', 'types'));
    }

    public function update(Request $request, Annonce $annonce): RedirectResponse
    {
        try {
            $data = $request->validate([
                'titre'     => ['required', 'string', 'max:255'],
                'contenu'   => ['required', 'string'],
                'type'      => ['required', 'in:' . implode(',', array_keys(Annonce::TYPES))],
                'statut'    => ['required', 'in:brouillon,publiee,archivee'],
                'expire_at' => ['nullable', 'date'],
            ]);

            if ($data['statut'] === Annonce::STATUT_PUBLIEE && ! $annonce->publiee_at) {
                $data['publiee_at'] = now();
            }

            $annonce->update($data);

            return redirect()->route('admin.annonces.index')->with('status', 'Annonce mise à jour.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function destroy(Annonce $annonce): RedirectResponse
    {
        try {
            $annonce->delete();
            return redirect()->route('admin.annonces.index')->with('status', 'Annonce supprimée.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
}
