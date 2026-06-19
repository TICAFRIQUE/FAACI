<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Projet extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'projets';

    protected $fillable = [
        'utilisateur_id',
        'titre',
        'slug',
        'description',
        'description_courte',
        'type_financement',
        'montant_cible',
        'montant_collecte',
        'statut',
        'motif_rejet',
        'valide_par',
        'date_validation',
        'date_debut',
        'date_fin_financement',
    ];

    protected function casts(): array
    {
        return [
            'montant_cible'          => 'decimal:2',
            'montant_collecte'       => 'decimal:2',
            'date_validation'        => 'datetime',
            'date_debut'             => 'date',
            'date_fin_financement'   => 'date',
        ];
    }

    // Statuts
    public const STATUT_BROUILLON      = 'brouillon';
    public const STATUT_EN_ATTENTE     = 'en_attente';
    public const STATUT_VALIDE         = 'valide';
    public const STATUT_EN_FINANCEMENT = 'en_financement';
    public const STATUT_FINANCE        = 'finance';
    public const STATUT_EN_COURS       = 'en_cours';
    public const STATUT_TERMINE        = 'termine';
    public const STATUT_REJETE         = 'rejete';

    public static function statutsLibelles(): array
    {
        return [
            self::STATUT_BROUILLON      => 'Brouillon',
            self::STATUT_EN_ATTENTE     => 'En attente',
            self::STATUT_VALIDE         => 'Validé',
            self::STATUT_EN_FINANCEMENT => 'En financement',
            self::STATUT_FINANCE        => 'Financé',
            self::STATUT_EN_COURS       => 'En cours',
            self::STATUT_TERMINE        => 'Terminé',
            self::STATUT_REJETE         => 'Rejeté',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->singleFile();
        $this->addMediaCollection('documents');
    }

    public function getImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('images') ?: '';
    }

    public function getPourcentageAttribute(): int
    {
        if (! $this->montant_cible || $this->montant_cible <= 0) {
            return 0;
        }

        return (int) min(100, round($this->montant_collecte / $this->montant_cible * 100));
    }

    // Relations
    public function porteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(Contribution::class, 'projet_id');
    }

    public function contributionsValides(): HasMany
    {
        return $this->hasMany(Contribution::class, 'projet_id')
            ->whereIn('statut', ['paid', 'partial']);
    }
}
