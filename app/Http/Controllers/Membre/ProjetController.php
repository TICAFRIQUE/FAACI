<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Http\Requests\Membre\ProjetRequest;
use App\Models\Projet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjetController extends Controller
{
    /** Liste des projets en financement (tous membres) */
    public function index(Request $request): View
    {
        $query = Projet::with('porteur')
            ->where('statut', Projet::STATUT_EN_FINANCEMENT)
            ->latest();

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('titre', 'like', "%{$q}%")
                    ->orWhere('description_courte', 'like', "%{$q}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type_financement', $request->input('type'));
        }

        $projets = $query->paginate(12)->withQueryString();

        return view('membre.projets.index', compact('projets'));
    }

    /** Mes projets (porteur) */
    public function mesProjets(): View
    {
        $projets = Projet::where('utilisateur_id', auth()->id())
            ->withCount('contributions')
            ->latest()
            ->get();

        return view('membre.projets.mes-projets', compact('projets'));
    }

    /** Formulaire soumission */
    public function create(): View
    {
        return view('membre.projets.create');
    }

    /** Enregistrer un projet */
    public function store(ProjetRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['utilisateur_id'] = auth()->id();
            $data['slug']           = $this->uniqueSlug($data['titre']);
            $data['statut']         = Projet::STATUT_BROUILLON;

            // Budget ouvert → pas de montant cible
            if (($data['type_financement'] ?? null) === 'ouvert') {
                $data['montant_cible'] = null;
            }

            unset($data['image'], $data['document']);

            $projet = Projet::create($data);

            if ($request->hasFile('image')) {
                $projet->addMediaFromRequest('image')->toMediaCollection('images');
            }

            if ($request->hasFile('document')) {
                $projet->addMediaFromRequest('document')->toMediaCollection('documents');
            }

            return redirect()->route('membre.projets.show', $projet)
                ->with('status', 'Projet créé. Soumettez-le pour validation admin.');
        } catch (\Throwable $e) {
            Log::error('Erreur création projet : '.$e->getMessage());

            return back()->withInput()->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    /** Détail d'un projet */
    public function show(Projet $projet): View
    {
        $peutVoir = in_array($projet->statut, [
            Projet::STATUT_EN_FINANCEMENT,
            Projet::STATUT_FINANCE,
            Projet::STATUT_EN_COURS,
            Projet::STATUT_TERMINE,
        ]) || $projet->utilisateur_id === auth()->id();

        abort_unless($peutVoir, 403);

        $projet->load(['porteur', 'contributions.contributeur']);
        $maContribution = $projet->contributions()->where('utilisateur_id', auth()->id())->first();

        return view('membre.projets.show', compact('projet', 'maContribution'));
    }

    /** Formulaire édition (brouillon uniquement) */
    public function edit(Projet $projet): View
    {
        abort_unless($projet->utilisateur_id === auth()->id(), 403);
        abort_unless($projet->statut === Projet::STATUT_BROUILLON, 403);

        return view('membre.projets.edit', compact('projet'));
    }

    /** Mettre à jour */
    public function update(ProjetRequest $request, Projet $projet): RedirectResponse
    {
        abort_unless($projet->utilisateur_id === auth()->id(), 403);
        abort_unless($projet->statut === Projet::STATUT_BROUILLON, 403);

        try {
            $data = $request->validated();

            if (($data['type_financement'] ?? null) === 'ouvert') {
                $data['montant_cible'] = null;
            }

            unset($data['image'], $data['document']);

            $projet->update($data);

            if ($request->hasFile('image')) {
                $projet->addMediaFromRequest('image')->toMediaCollection('images');
            }

            if ($request->hasFile('document')) {
                $projet->addMediaFromRequest('document')->toMediaCollection('documents');
            }

            return redirect()->route('membre.projets.show', $projet)
                ->with('status', 'Projet mis à jour.');
        } catch (\Throwable $e) {
            Log::error('Erreur MAJ projet #'.$projet->id.' : '.$e->getMessage());

            return back()->withInput()->with('error', 'Une erreur est survenue.');
        }
    }

    /** Soumettre à la validation admin */
    public function soumettre(Projet $projet): RedirectResponse
    {
        abort_unless($projet->utilisateur_id === auth()->id(), 403);
        abort_unless($projet->statut === Projet::STATUT_BROUILLON, 403);

        try {
            $projet->update(['statut' => Projet::STATUT_EN_ATTENTE]);

            return redirect()->route('membre.projets.show', $projet)
                ->with('status', 'Votre projet a été soumis. Un administrateur va l\'examiner.');
        } catch (\Throwable $e) {
            Log::error('Erreur soumission projet #'.$projet->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /** Supprimer (brouillon uniquement) */
    public function destroy(Projet $projet): RedirectResponse
    {
        abort_unless($projet->utilisateur_id === auth()->id(), 403);
        abort_unless($projet->statut === Projet::STATUT_BROUILLON, 403);

        try {
            $projet->delete();

            return redirect()->route('membre.projets.mes-projets')
                ->with('status', 'Projet supprimé.');
        } catch (\Throwable $e) {
            Log::error('Erreur suppression projet #'.$projet->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    private function uniqueSlug(string $titre): string
    {
        $base = Str::slug($titre);
        $slug = $base;
        $i    = 1;
        while (Projet::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
