<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PaiementCotisation extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'paiements_cotisation';

    protected $fillable = [
        'utilisateur_id',
        'type_cotisation_id',
        'montant',
        'moyen_paiement',
        'note',
        'saisi_par',
        'statut',
        'motif_rejet',
        'valide_par',
        'date_validation',
        'numero_transaction',
        'mois_couverts',
    ];

    protected function casts(): array
    {
        return [
            'montant'          => 'decimal:2',
            'date_validation'  => 'datetime',
            'mois_couverts'    => 'array',
        ];
    }

    public const STATUT_EN_ATTENTE = 'en_attente';
    public const STATUT_VALIDE     = 'valide';
    public const STATUT_REJETE     = 'rejete';

    public static function moyensPaiement(): array
    {
        return [
            'cash'         => 'Cash / Espèces',
            'orange_money' => 'Orange Money',
            'wave'         => 'Wave',
            'virement'     => 'Virement bancaire',
            'autre'        => 'Autre',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('preuves')->singleFile();
    }

    public function membre(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TypeCotisation::class, 'type_cotisation_id');
    }

    public function saiseur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'saisi_par');
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function periodes(): BelongsToMany
    {
        return $this->belongsToMany(PeriodeCotisation::class, 'paiement_periode', 'paiement_cotisation_id', 'periode_cotisation_id')
                    ->withPivot('montant_attribue')
                    ->orderBy('date_debut');
    }

    /**
     * Applique le paiement validé aux lignes cotisation des périodes sélectionnées.
     * Logique FIFO par ordre de date_debut : remplit chaque période dans l'ordre.
     */
    public function appliquer(): void
    {
        DB::transaction(function () {
            $periodes = $this->periodes()->orderBy('date_debut')->get();
            $restant  = (float) $this->montant;

            foreach ($periodes as $periode) {
                if ($restant <= 0) break;

                $cotisation = Cotisation::where('utilisateur_id', $this->utilisateur_id)
                    ->where('periode_cotisation_id', $periode->id)
                    ->first();

                if (!$cotisation) continue;

                $resteACouvrir  = $cotisation->getReste();
                $aAttribuer     = min($restant, $resteACouvrir);

                if ($aAttribuer <= 0) continue;

                $nouveauPaye   = (float) $cotisation->montant_paye + $aAttribuer;
                $nouveauStatut = $nouveauPaye >= (float) $cotisation->montant_du
                    ? Cotisation::STATUT_VALIDE
                    : Cotisation::STATUT_PARTIEL;

                $cotisation->update([
                    'montant_paye' => $nouveauPaye,
                    'statut'       => $nouveauStatut,
                ]);

                // Met à jour montant_attribue dans le pivot
                $this->periodes()->updateExistingPivot($periode->id, ['montant_attribue' => $aAttribuer]);

                $restant -= $aAttribuer;
            }
        });
    }
}
