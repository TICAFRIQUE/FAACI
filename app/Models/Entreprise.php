<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Entreprise extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'entreprises';

    protected $fillable = [
        'utilisateur_id',
        'nom',
        'secteur',
        'description',
        'localisation',
        'site_web',
        'telephone',
        'email_contact',
        'annee_creation',
        'statut',
        'motif_rejet',
        'valide_par',
        'date_validation',
    ];

    protected function casts(): array
    {
        return [
            'date_validation' => 'datetime',
        ];
    }

    public const STATUT_EN_ATTENTE = 'en_attente';
    public const STATUT_ACTIF      = 'actif';
    public const STATUT_REJETE     = 'rejete';
    public const STATUT_INACTIF    = 'inactif';

    public static function statutsLibelles(): array
    {
        return [
            self::STATUT_EN_ATTENTE => 'En attente',
            self::STATUT_ACTIF      => 'Active',
            self::STATUT_REJETE     => 'Rejetée',
            self::STATUT_INACTIF    => 'Inactive',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logos')->singleFile();
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('logos') ?: '';
    }

    public function proprietaire(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }
}
