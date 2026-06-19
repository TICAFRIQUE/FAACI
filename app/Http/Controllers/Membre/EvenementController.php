<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\InscriptionEvenement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvenementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Evenement::where('statut', Evenement::STATUT_PUBLIE)
            ->withCount(['inscriptions as nb_inscrits' => fn ($q) => $q->whereIn('statut', ['inscrit', 'confirme'])])
            ->orderBy('date_debut');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('periode')) {
            match ($request->periode) {
                'a_venir' => $query->where('date_debut', '>=', now()),
                'passes'  => $query->where('date_debut', '<', now()),
                default   => null,
            };
        } else {
            // Par défaut : à venir en premier
            $query->where('date_debut', '>=', now()->startOfDay());
        }

        $evenements = $query->paginate(12)->withQueryString();

        $monInscription = auth()->check()
            ? InscriptionEvenement::where('utilisateur_id', auth()->id())
                ->whereIn('evenement_id', $evenements->pluck('id'))
                ->get()
                ->keyBy('evenement_id')
            : collect();

        return view('membre.evenements.index', compact('evenements', 'monInscription'));
    }

    public function show(Evenement $evenement): View
    {
        abort_unless($evenement->statut === Evenement::STATUT_PUBLIE, 404);

        $evenement->load(['organisateur']);

        $monInscription = InscriptionEvenement::where('evenement_id', $evenement->id)
            ->where('utilisateur_id', auth()->id())
            ->first();

        $nbInscrits = $evenement->inscriptions()
            ->whereIn('statut', ['inscrit', 'confirme'])
            ->count();

        return view('membre.evenements.show', compact('evenement', 'monInscription', 'nbInscrits'));
    }

    public function inscrire(Evenement $evenement): RedirectResponse
    {
        abort_unless($evenement->statut === Evenement::STATUT_PUBLIE, 403);
        abort_unless(! $evenement->est_passe, 403, 'Cet événement est passé.');

        try {
            // Vérifier capacité
            if ($evenement->capacite_max) {
                $nbInscrits = $evenement->inscriptions()
                    ->whereIn('statut', ['inscrit', 'confirme'])
                    ->count();

                if ($nbInscrits >= $evenement->capacite_max) {
                    return back()->with('error', 'Capacité maximale atteinte pour cet événement.');
                }
            }

            InscriptionEvenement::updateOrCreate(
                ['evenement_id' => $evenement->id, 'utilisateur_id' => auth()->id()],
                ['statut' => InscriptionEvenement::STATUT_INSCRIT]
            );

            Evenement::clearCache();

            return back()->with('status', 'Vous êtes inscrit(e) à cet événement.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    public function calendrierJson(Request $request): JsonResponse
    {
        $start = $request->filled('start') ? $request->date('start') : now()->startOfMonth();
        $end   = $request->filled('end')   ? $request->date('end')   : now()->endOfMonth();

        $evenements = Evenement::where('statut', Evenement::STATUT_PUBLIE)
            ->where('date_debut', '<=', $end)
            ->where(function ($q) use ($start) {
                $q->whereNull('date_fin')->where('date_debut', '>=', $start)
                  ->orWhere('date_fin', '>=', $start);
            })
            ->get();

        $mesIds = InscriptionEvenement::where('utilisateur_id', auth()->id())
            ->whereIn('evenement_id', $evenements->pluck('id'))
            ->whereIn('statut', ['inscrit', 'confirme'])
            ->pluck('evenement_id')
            ->flip();

        $couleurs = [
            'reunion'    => '#0D1F3C',
            'pitch'      => '#4A7FA5',
            'webinaire'  => '#2e7d32',
            'networking' => '#e65100',
            'ag'         => '#6a1b9a',
        ];

        $events = $evenements->map(function (Evenement $ev) use ($mesIds, $couleurs) {
            $inscrit = $mesIds->has($ev->id);
            return [
                'id'    => $ev->id,
                'title' => $ev->titre,
                'start' => $ev->date_debut->toIso8601String(),
                'end'   => $ev->date_fin?->toIso8601String(),
                'url'   => route('membre.evenements.show', $ev),
                'backgroundColor' => $couleurs[$ev->type] ?? '#0D1F3C',
                'borderColor'     => $inscrit ? '#ffc107' : ($couleurs[$ev->type] ?? '#0D1F3C'),
                'extendedProps'   => [
                    'type_libelle' => $ev->type_libelle,
                    'lieu'         => $ev->lieu,
                    'inscrit'      => $inscrit,
                    'complet'      => $ev->est_complet,
                ],
            ];
        });

        return response()->json($events);
    }

    public function desinscrire(Evenement $evenement): RedirectResponse
    {
        try {
            InscriptionEvenement::where('evenement_id', $evenement->id)
                ->where('utilisateur_id', auth()->id())
                ->delete();

            Evenement::clearCache();

            return back()->with('status', 'Vous êtes désinscrit(e) de cet événement.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Une erreur est survenue.');
        }
    }
}
