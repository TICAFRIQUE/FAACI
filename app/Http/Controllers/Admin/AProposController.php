<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContenuSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AProposController extends Controller
{
    /**
     * @var array<string, string>
     */
    private const GROUPES = [
        'apropos_mission' => 'Mission',
        'apropos_vision' => 'Vision',
        'apropos_histoire' => 'Histoire',
    ];

    public function edit(): View
    {
        $contenus = ContenuSection::whereIn('groupe', array_keys(self::GROUPES))
            ->orderBy('groupe')
            ->orderBy('ordre')
            ->get()
            ->groupBy('groupe');

        return view('admin.apropos.edit', [
            'groupes' => self::GROUPES,
            'contenus' => $contenus,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contenus' => ['required', 'array'],
            'contenus.*' => ['nullable', 'string', 'max:5000'],
        ]);

        $contenus = ContenuSection::whereIn('groupe', array_keys(self::GROUPES))
            ->whereIn('cle', array_keys($validated['contenus']))
            ->get();

        foreach ($contenus as $contenu) {
            $contenu->update(['valeur' => $validated['contenus'][$contenu->cle]]);
        }

        return redirect()->route('admin.apropos.edit')->with('status', 'Page "À propos" mise à jour avec succès.');
    }
}
