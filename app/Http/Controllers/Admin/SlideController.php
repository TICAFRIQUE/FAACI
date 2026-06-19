<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SlideRequest;
use App\Models\Slide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SlideController extends Controller
{
    public function index(): View
    {
        $slides = Slide::orderBy('ordre')->get();

        return view('admin.slides.index', compact('slides'));
    }

    public function create(): View
    {
        return view('admin.slides.create');
    }

    public function store(SlideRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');

        $slide = Slide::create([
            ...$data,
            'actif' => $request->boolean('actif'),
            'ordre' => (Slide::max('ordre') ?? 0) + 1,
        ]);

        if ($request->hasFile('image')) {
            $slide->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return redirect()->route('admin.slides.index')->with('status', 'Slide créée avec succès.');
    }

    public function edit(Slide $slide): View
    {
        return view('admin.slides.edit', compact('slide'));
    }

    public function update(SlideRequest $request, Slide $slide): RedirectResponse
    {
        $data = $request->safe()->except('image');

        $slide->update([
            ...$data,
            'actif' => $request->boolean('actif'),
        ]);

        if ($request->hasFile('image')) {
            $slide->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return redirect()->route('admin.slides.index')->with('status', 'Slide mise à jour avec succès.');
    }

    public function destroy(Slide $slide): RedirectResponse
    {
        $slide->delete();

        return redirect()->route('admin.slides.index')->with('status', 'Slide supprimée avec succès.');
    }

    public function basculer(Slide $slide): RedirectResponse
    {
        $slide->update(['actif' => ! $slide->actif]);

        return redirect()->route('admin.slides.index')->with('status', 'Statut de la slide mis à jour.');
    }

    public function deplacer(Request $request, Slide $slide): RedirectResponse
    {
        $direction = $request->input('direction');

        $voisin = $direction === 'haut'
            ? Slide::where('ordre', '<', $slide->ordre)->orderByDesc('ordre')->first()
            : Slide::where('ordre', '>', $slide->ordre)->orderBy('ordre')->first();

        if ($voisin) {
            $ordreSlide = $slide->ordre;
            $slide->update(['ordre' => $voisin->ordre]);
            $voisin->update(['ordre' => $ordreSlide]);
        }

        return redirect()->route('admin.slides.index');
    }
}
