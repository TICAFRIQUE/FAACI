<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeCotisation extends Model
{
    protected $table = 'periodes_cotisation';

    protected $fillable = [
        'type_cotisation_id',
        'libelle',
        'date_debut',
        'date_fin_paiement',
        'montant_standard',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'date_debut'        => 'date',
            'date_fin_paiement' => 'date',
            'montant_standard'  => 'decimal:2',
        ];
    }

    public const STATUT_OUVERTE  = 'ouverte';
    public const STATUT_FERMEE   = 'fermee';
    public const STATUT_ARCHIVEE = 'archivee';

    public function type(): BelongsTo
    {
        return $this->belongsTo(TypeCotisation::class, 'type_cotisation_id');
    }

    public function cotisations(): HasMany
    {
        return $this->hasMany(Cotisation::class, 'periode_cotisation_id');
    }

    public function paiements(): BelongsToMany
    {
        return $this->belongsToMany(PaiementCotisation::class, 'paiement_periode', 'periode_cotisation_id', 'paiement_cotisation_id')
                    ->withPivot('montant_attribue');
    }

    /** Génère les lignes cotisation pour tous les membres actifs qui n'en ont pas encore. */
    public function genererPourMembres(): int
    {
        $membres = User::role('membre')->where('statut', User::STATUT_ACTIF)->get();
        $count   = 0;

        foreach ($membres as $membre) {
            $existe = Cotisation::where('utilisateur_id', $membre->id)
                ->where('periode_cotisation_id', $this->id)
                ->exists();

            if (!$existe) {
                Cotisation::create([
                    'utilisateur_id'       => $membre->id,
                    'periode_cotisation_id'=> $this->id,
                    'montant_du'           => $this->montant_standard,
                    'montant_paye'         => 0,
                    'statut'               => Cotisation::STATUT_EN_ATTENTE,
                ]);
                $count++;
            }
        }

        return $count;
    }

    /** Génère les lignes cotisation pour un seul membre (ex: nouveau membre activé). */
    public function genererPourMembre(User $membre): void
    {
        Cotisation::firstOrCreate(
            ['utilisateur_id' => $membre->id, 'periode_cotisation_id' => $this->id],
            ['montant_du' => $this->montant_standard, 'montant_paye' => 0, 'statut' => Cotisation::STATUT_EN_ATTENTE]
        );
    }
}
