<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AlbumGalerieRequest;
use App\Models\AlbumGalerie;
use App\Models\ImageGalerie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlbumGalerieController extends Controller
{
    public function index(): View
    {
        $albums = AlbumGalerie::withCount('images')->orderBy('ordre')->get();

        return view('admin.galerie.index', compact('albums'));
    }

    public function create(): View
    {
        return view('admin.galerie.create');
    }

    public function store(AlbumGalerieRequest $request): RedirectResponse
    {
        AlbumGalerie::create([
            ...$request->validated(),
            'actif' => $request->boolean('actif'),
            'ordre' => (AlbumGalerie::max('ordre') ?? 0) + 1,
        ]);

        return redirect()->route('admin.galerie.index')->with('status', 'Album créé avec succès.');
    }

    public function edit(AlbumGalerie $galerie): View
    {
        $galerie->load('images.media');

        return view('admin.galerie.edit', ['album' => $galerie]);
    }

    public function update(AlbumGalerieRequest $request, AlbumGalerie $galerie): RedirectResponse
    {
        $galerie->update([
            ...$request->validated(),
            'actif' => $request->boolean('actif'),
        ]);

        return redirect()->route('admin.galerie.index')->with('status', 'Album mis à jour avec succès.');
    }

    public function destroy(AlbumGalerie $galerie): RedirectResponse
    {
        $galerie->delete();

        return redirect()->route('admin.galerie.index')->with('status', 'Album supprimé avec succès.');
    }

    public function basculer(AlbumGalerie $galerie): RedirectResponse
    {
        $galerie->update(['actif' => ! $galerie->actif]);

        return redirect()->route('admin.galerie.index')->with('status', "Statut de l'album mis à jour.");
    }

    public function deplacer(Request $request, AlbumGalerie $galerie): RedirectResponse
    {
        $direction = $request->input('direction');

        $voisin = $direction === 'haut'
            ? AlbumGalerie::where('ordre', '<', $galerie->ordre)->orderByDesc('ordre')->first()
            : AlbumGalerie::where('ordre', '>', $galerie->ordre)->orderBy('ordre')->first();

        if ($voisin) {
            $ordreAlbum = $galerie->ordre;
            $galerie->update(['ordre' => $voisin->ordre]);
            $voisin->update(['ordre' => $ordreAlbum]);
        }

        return redirect()->route('admin.galerie.index');
    }

    public function storeImages(Request $request, AlbumGalerie $galerie): RedirectResponse
    {
        $request->validate([
            'images'   => ['required', 'array'],
            'images.*' => ['image', 'max:1024'],
        ], [
            'images.*.max'   => 'Chaque photo ne doit pas dépasser 1 Mo.',
            'images.*.image' => 'Chaque fichier doit être une image (jpg, png, gif, webp…).',
        ]);

        $ordre = $galerie->images()->max('ordre') ?? 0;

        foreach ($request->file('images') as $fichier) {
            $ordre++;

            $image = ImageGalerie::create([
                'album_galerie_id' => $galerie->id,
                'ordre' => $ordre,
            ]);

            $image->addMedia($fichier)->toMediaCollection('image');
        }

        return redirect()->route('admin.galerie.edit', $galerie)->with('status', 'Images ajoutées avec succès.');
    }

    public function destroyImage(AlbumGalerie $galerie, ImageGalerie $image): RedirectResponse
    {
        $image->delete();

        return redirect()->route('admin.galerie.edit', $galerie)->with('status', 'Image supprimée avec succès.');
    }

    public function deplacerImage(Request $request, AlbumGalerie $galerie, ImageGalerie $image): RedirectResponse
    {
        $direction = $request->input('direction');

        $voisin = $direction === 'haut'
            ? $galerie->images()->where('ordre', '<', $image->ordre)->orderByDesc('ordre')->first()
            : $galerie->images()->where('ordre', '>', $image->ordre)->orderBy('ordre')->first();

        if ($voisin) {
            $ordreImage = $image->ordre;
            $image->update(['ordre' => $voisin->ordre]);
            $voisin->update(['ordre' => $ordreImage]);
        }

        return redirect()->route('admin.galerie.edit', $galerie);
    }
}
