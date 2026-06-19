<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContenuSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AccueilController extends Controller
{
    /**
     * @var array<string, string>
     */
    private const GROUPES = [
        'accueil_about' => "À propos (aperçu sur l'accueil)",
        'accueil_stats' => 'Statistiques',
        'accueil_activites' => 'Activités (introduction)',
        'accueil_rejoindre' => 'Comment rejoindre',
        'accueil_cta' => "Appel à l'adhésion",
    ];

    public function edit(): View
    {
        $contenus = ContenuSection::whereIn('groupe', array_keys(self::GROUPES))
            ->orderBy('groupe')
            ->orderBy('ordre')
            ->get()
            ->groupBy('groupe');

        return view('admin.accueil.edit', [
            'groupes' => self::GROUPES,
            'contenus' => $contenus,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'contenus'   => ['nullable', 'array'],
                'contenus.*' => ['nullable', 'string', 'max:5000'],
                'images'     => ['nullable', 'array'],
                'images.*'   => ['nullable', 'image', 'max:1024'],
            ], [
                'images.*.max'   => "L'image ne doit pas dépasser 1 Mo.",
                'images.*.image' => 'Le fichier doit être une image.',
            ]);

            $tousContenus = ContenuSection::whereIn('groupe', array_keys(self::GROUPES))->get();

            foreach ($tousContenus as $contenu) {
                if ($contenu->type === ContenuSection::TYPE_IMAGE) {
                    if ($request->hasFile("images.{$contenu->cle}")) {
                        $file = $request->file("images.{$contenu->cle}");
                        $path = $file->store('sections', 'public');
                        $contenu->update(['valeur' => Storage::disk('public')->url($path)]);
                    }
                } elseif (isset($request->contenus[$contenu->cle])) {
                    $contenu->update(['valeur' => $request->contenus[$contenu->cle]]);
                }
            }

            return redirect()->route('admin.accueil.edit')->with('status', "Contenu de la page d'accueil mis à jour avec succès.");
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }
}
