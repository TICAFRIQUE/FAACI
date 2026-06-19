<?php

namespace App\Http\Controllers;

use App\Models\AlbumGalerie;
use Illuminate\View\View;

class GalerieController extends Controller
{
    public function index(): View
    {
        return view('public.galerie.index', [
            'albums' => AlbumGalerie::actifs(),
        ]);
    }

    public function show(AlbumGalerie $galerie): View
    {
        abort_unless($galerie->actif, 404);

        $galerie->load('images.media');

        return view('public.galerie.show', ['album' => $galerie]);
    }
}
