<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Admin\CotisationAdminController;
use App\Http\Controllers\Controller;
use App\Models\PaiementCotisation;
use App\Models\TypeCotisation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CotisationMembreController extends Controller
{
    public function index(): View
    {
        /** @var User $membre */
        $membre = auth()->user();

        $types      = TypeCotisation::where('actif', true)->whereNotNull('date_debut')->orderBy('nom')->get();
        $calendriers = [];

        foreach ($types as $type) {
            $calendriers[] = [
                'type'       => $type,
                'calendrier' => CotisationAdminController::buildCalendrier($membre, $type),
            ];
        }

        // Mois déjà couverts (valide ou en_attente) par type → pour désactiver dans le picker
        $moisDejaCouverts = [];
        foreach ($calendriers as $bloc) {
            $tid  = $bloc['type']->id;
            $mois = [];
            foreach ($bloc['calendrier'] as $moisData) {
                foreach ($moisData as $cell) {
                    if (in_array($cell['statut'], ['valide', 'en_attente'])) {
                        $mois[] = $cell['mois'];
                    }
                }
            }
            $moisDejaCouverts[$tid] = $mois;
        }

        $paiements = PaiementCotisation::with('type')
            ->where('utilisateur_id', $membre->id)
            ->latest()
            ->get();

        return view('membre.cotisations.index', compact('membre', 'types', 'calendriers', 'paiements', 'moisDejaCouverts'));
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $membre */
        $membre = auth()->user();

        $request->validate([
            'type_cotisation_id' => ['required', 'exists:types_cotisation,id'],
            'mois_couverts'      => ['required', 'array', 'min:1'],
            'mois_couverts.*'    => ['string', 'regex:/^\d{4}-\d{2}$/'],
            'montant'            => ['required', 'numeric', 'min:1'],
            'moyen_paiement'     => ['required', 'in:'.implode(',', array_keys(PaiementCotisation::moyensPaiement()))],
            'numero_transaction' => ['nullable', 'string', 'max:100'],
            'note'               => ['nullable', 'string', 'max:500'],
            'preuve'             => ['nullable', 'file', 'max:3072', 'mimes:jpg,jpeg,png,pdf,webp'],
        ]);

        // Validation FIFO
        $error = $this->validerFifo($membre->id, (int)$request->type_cotisation_id, $request->mois_couverts);
        if ($error) {
            return back()->withInput()->with('error', $error);
        }

        try {
            $paiement = PaiementCotisation::create([
                'utilisateur_id'     => $membre->id,
                'type_cotisation_id' => $request->type_cotisation_id,
                'montant'            => $request->montant,
                'moyen_paiement'     => $request->moyen_paiement,
                'numero_transaction' => $request->numero_transaction,
                'mois_couverts'      => $request->mois_couverts,
                'note'               => $request->note,
                'saisi_par'          => null,
                'statut'             => PaiementCotisation::STATUT_EN_ATTENTE,
            ]);

            if ($request->hasFile('preuve')) {
                $paiement->addMediaFromRequest('preuve')->toMediaCollection('preuves');
            }

            return redirect()->route('membre.cotisations.index')
                ->with('status', 'Déclaration enregistrée. Elle sera traitée par notre équipe.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur : '.$e->getMessage());
        }
    }

    /**
     * Vérifie FIFO : tous les mois passés non payés antérieurs au plus ancien sélectionné
     * doivent être inclus dans la sélection.
     */
    private function validerFifo(int $userId, int $typeId, array $moisSelectionnes): ?string
    {
        if (empty($moisSelectionnes)) return null;

        sort($moisSelectionnes);
        $plusAncien = $moisSelectionnes[0];

        // Paiements déjà couverts (valide OU en_attente) → doublon interdit
        $paiementsExistants = PaiementCotisation::where('utilisateur_id', $userId)
            ->where('type_cotisation_id', $typeId)
            ->whereIn('statut', [PaiementCotisation::STATUT_VALIDE, PaiementCotisation::STATUT_EN_ATTENTE])
            ->get();

        $moisDejaCouvert = $paiementsExistants
            ->flatMap(fn($p) => $p->mois_couverts ?? [])
            ->unique()
            ->all();

        $doublons = array_intersect($moisSelectionnes, $moisDejaCouvert);
        if (!empty($doublons)) {
            $noms = array_map(
                fn($m) => \Carbon\Carbon::parse($m.'-01')->translatedFormat('M Y'),
                array_values($doublons)
            );
            return 'Les mois suivants sont déjà couverts : '.implode(', ', $noms).'.';
        }

        // Paiements déjà validés pour ce type/membre (FIFO : uniquement sur les validés)
        $moisDejaPaies = $paiementsExistants
            ->where('statut', PaiementCotisation::STATUT_VALIDE)
            ->flatMap(fn($p) => $p->mois_couverts ?? [])
            ->unique()
            ->all();

        // Type pour connaître la date de début
        $type = TypeCotisation::find($typeId);
        if (!$type?->date_debut) return null;

        $debut   = $type->date_debut->format('Y-m');
        $current = $debut;

        // Parcourir les mois depuis le début jusqu'au plus ancien sélectionné
        while ($current < $plusAncien) {
            $estPayé     = in_array($current, $moisDejaPaies);
            $estSelecte  = in_array($current, $moisSelectionnes);

            if (!$estPayé && !$estSelecte) {
                return "Vous devez d'abord inclure le mois {$current} (non encore soldé) avant de payer des mois ultérieurs.";
            }

            // Avancer d'un mois
            [$y, $m] = explode('-', $current);
            $m = (int)$m + 1;
            if ($m > 12) { $m = 1; $y = (int)$y + 1; }
            $current = $y.'-'.str_pad($m, 2, '0', STR_PAD_LEFT);
        }

        return null;
    }
}
