<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cotisation extends Model
{
    protected $table = 'cotisations';

    protected $fillable = [
        'utilisateur_id',
        'periode_cotisation_id',
        'montant_du',
        'montant_paye',
        'statut',
        'motif_exoneration',
    ];

    protected function casts(): array
    {
        return [
            'montant_du'   => 'decimal:2',
            'montant_paye' => 'decimal:2',
        ];
    }

    public const STATUT_EN_ATTENTE = 'en_attente';
    public const STATUT_PARTIEL    = 'partiel';
    public const STATUT_VALIDE     = 'valide';
    public const STATUT_EN_RETARD  = 'en_retard';
    public const STATUT_EXONERE    = 'exonere';

    public static function statutsLibelles(): array
    {
        return [
            self::STATUT_EN_ATTENTE => 'En attente',
            self::STATUT_PARTIEL    => 'Partiel',
            self::STATUT_VALIDE     => 'Soldé',
            self::STATUT_EN_RETARD  => 'En retard',
            self::STATUT_EXONERE    => 'Exonéré',
        ];
    }

    /** Couleur Bootstrap badge selon statut. */
    public static function statutCouleurs(): array
    {
        return [
            self::STATUT_EN_ATTENTE => 'primary',
            self::STATUT_PARTIEL    => 'warning',
            self::STATUT_VALIDE     => 'success',
            self::STATUT_EN_RETARD  => 'danger',
            self::STATUT_EXONERE    => 'secondary',
        ];
    }

    public function membre(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeCotisation::class, 'periode_cotisation_id');
    }

    public function getReste(): float
    {
        return max(0, (float) $this->montant_du - (float) $this->montant_paye);
    }

    public function estSolde(): bool
    {
        return in_array($this->statut, [self::STATUT_VALIDE, self::STATUT_EXONERE]);
    }
}
