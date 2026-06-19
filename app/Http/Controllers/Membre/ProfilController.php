<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Http\Requests\Membre\ProfilUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function show(): View
    {
        return view('membre.profil.show', ['membre' => auth()->user()]);
    }

    public function edit(): View
    {
        return view('membre.profil.edit', [
            'membre'   => auth()->user(),
            'secteurs' => config('secteurs'),
            'villes'   => array_keys(config('ville-commune')),
            'anneeMin' => 2000,
            'anneeMax' => (int) date('Y'),
        ]);
    }

    public function update(ProfilUpdateRequest $request): RedirectResponse
    {
        try {
            $user = auth()->user();
            $data = $request->validated();

            // competences peut arriver comme tableau ou null
            $data['competences'] = array_filter(array_map('trim', $data['competences'] ?? []));

            $user->fill($data)->save();

            return redirect()->route('membre.profil')->with('status', 'Profil mis à jour avec succès.');
        } catch (\Throwable $e) {
            Log::error('Erreur mise à jour profil #'.auth()->id().' : '.$e->getMessage());

            return back()->withInput()->with('error', 'Une erreur est survenue lors de la mise à jour.');
        }
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ]);

        try {
            $user = auth()->user();
            $user->addMediaFromRequest('photo')->toMediaCollection('photos');

            return back()->with('status', 'Photo de profil mise à jour.');
        } catch (\Throwable $e) {
            Log::error('Erreur upload photo profil #'.auth()->id().' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de l\'upload de la photo.');
        }
    }

    public function deletePhoto(): RedirectResponse
    {
        try {
            auth()->user()->clearMediaCollection('photos');

            return back()->with('status', 'Photo supprimée.');
        } catch (\Throwable $e) {
            Log::error('Erreur suppression photo profil #'.auth()->id().' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }
}
