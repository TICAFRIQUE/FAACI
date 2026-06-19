<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParametreSite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ParametreSiteController extends Controller
{
    public function edit(): View
    {
        $parametres = ParametreSite::orderBy('ordre')->get();

        return view('admin.parametres.edit', compact('parametres'));
    }

    public function update(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'parametres'   => ['nullable', 'array'],
                'parametres.*' => ['nullable', 'string', 'max:2000'],
                'logo'         => ['nullable', 'image', 'max:1024'],
            ], [
                'logo.max'   => 'Le logo ne doit pas dépasser 1 Mo.',
                'logo.image' => 'Le fichier doit être une image (jpg, png, gif, webp…).',
            ]);

            if ($request->filled('parametres')) {
                $parametres = ParametreSite::whereIn('cle', array_keys($request->parametres))->get();
                foreach ($parametres as $parametre) {
                    $parametre->update(['valeur' => $request->parametres[$parametre->cle]]);
                }
            }

            if ($request->hasFile('logo')) {
                $path      = $request->file('logo')->storeAs('logo', 'logo.jpg', 'public');
                $logoParam = ParametreSite::firstOrCreate(
                    ['cle' => 'logo'],
                    ['libelle' => 'Logo du site (header & footer)', 'valeur' => '', 'ordre' => 0]
                );
                $logoParam->update(['valeur' => Storage::disk('public')->url($path)]);
            }

            return redirect()->route('admin.parametres.edit')->with('status', 'Paramètres du site mis à jour avec succès.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }
}
