<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Don extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'dons';

    protected $fillable = [
        'utilisateur_id',
        'nature',
        'libelle',
        'montant',
        'valeur_estimee',
        'moyen_paiement',
        'description',
        'statut',
        'motif_rejet',
        'valide_par',
        'date_validation',
    ];

    protected function casts(): array
    {
        return [
            'montant'          => 'decimal:2',
            'date_validation'  => 'datetime',
        ];
    }

    public const STATUT_EN_ATTENTE = 'en_attente';
    public const STATUT_CONFIRME   = 'confirme';
    public const STATUT_REJETE     = 'rejete';
    public const STATUT_ANNULE     = 'annule';

    public static function natures(): array
    {
        return [
            'argent'   => 'Don financier',
            'materiel' => 'Don matériel',
            'autre'    => 'Autre contribution',
        ];
    }

    public static function statutsLibelles(): array
    {
        return [
            self::STATUT_EN_ATTENTE => 'En attente',
            self::STATUT_CONFIRME   => 'Confirmé',
            self::STATUT_REJETE     => 'Rejeté',
            self::STATUT_ANNULE     => 'Annulé',
        ];
    }

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

    public function donateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }
}
