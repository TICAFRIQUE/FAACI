<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EvenementRequest;
use App\Models\Evenement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EvenementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Evenement::withCount('inscriptions')->orderByDesc('date_debut');

        if ($request->filled('statut') && $request->statut !== 'tous') {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('q')) {
            $query->where('titre', 'like', '%'.$request->q.'%');
        }

        $evenements = $query->paginate(15)->withQueryString();

        $compteurs = [
            'tous'      => Evenement::count(),
            'brouillon' => Evenement::where('statut', 'brouillon')->count(),
            'publie'    => Evenement::where('statut', 'publie')->count(),
        ];

        return view('admin.evenements.index', compact('evenements', 'compteurs'));
    }

    public function create(): View
    {
        return view('admin.evenements.create');
    }

    public function store(EvenementRequest $request): RedirectResponse
    {
        try {
            $data = $request->safe()->except('image');
            $data['slug']        = $this->uniqueSlug($data['titre']);
            $data['est_public']  = $request->boolean('est_public');
            $data['organisateur_id'] = auth()->id();

            $evenement = Evenement::create($data);

            if ($request->hasFile('image')) {
                $evenement->addMediaFromRequest('image')->toMediaCollection('image');
            }

            Evenement::clearCache();

            return redirect()->route('admin.evenements.index')
                ->with('status', 'Événement créé avec succès.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Une erreur est survenue : '.$e->getMessage());
        }
    }

    public function show(Evenement $evenement): View
    {
        $evenement->load(['organisateur', 'inscriptions.utilisateur']);

        return view('admin.evenements.show', compact('evenement'));
    }

    public function edit(Evenement $evenement): View
    {
        return view('admin.evenements.edit', compact('evenement'));
    }

    public function update(EvenementRequest $request, Evenement $evenement): RedirectResponse
    {
        try {
            $data = $request->safe()->except('image');
            $data['est_public'] = $request->boolean('est_public');

            $evenement->update($data);

            if ($request->hasFile('image')) {
                $evenement->addMediaFromRequest('image')->toMediaCollection('image');
            }

            Evenement::clearCache();

            return redirect()->route('admin.evenements.show', $evenement)
                ->with('status', 'Événement mis à jour.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Une erreur est survenue.');
        }
    }

    public function destroy(Evenement $evenement): RedirectResponse
    {
        try {
            $evenement->delete();
            Evenement::clearCache();

            return redirect()->route('admin.evenements.index')
                ->with('status', 'Événement supprimé.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    public function basculerStatut(Evenement $evenement): RedirectResponse
    {
        $nouveau = $evenement->statut === Evenement::STATUT_PUBLIE
            ? Evenement::STATUT_BROUILLON
            : Evenement::STATUT_PUBLIE;

        $evenement->update(['statut' => $nouveau]);
        Evenement::clearCache();

        $msg = $nouveau === Evenement::STATUT_PUBLIE ? 'publié' : 'dépublié';

        return back()->with('status', "Événement {$msg}.");
    }

    private function uniqueSlug(string $titre): string
    {
        $base = Str::slug($titre);
        $slug = $base;
        $i    = 1;
        while (Evenement::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
