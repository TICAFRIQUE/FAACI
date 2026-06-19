<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Contribution extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'contributions';

    protected $fillable = [
        'projet_id',
        'utilisateur_id',
        'montant_promis',
        'montant_paye',
        'statut',
        'note',
        'date_declaration_paiement',
        'valide_par',
        'date_validation',
        'motif_rejet',
        'moyen_paiement',
    ];

    protected function casts(): array
    {
        return [
            'montant_promis'            => 'decimal:2',
            'montant_paye'              => 'decimal:2',
            'date_declaration_paiement' => 'datetime',
            'date_validation'           => 'datetime',
        ];
    }

    public const STATUT_PENDING   = 'pending';
    public const STATUT_CONFIRMED = 'confirmed';
    public const STATUT_PAID      = 'paid';
    public const STATUT_PARTIAL   = 'partial';
    public const STATUT_CANCELLED = 'cancelled';

    public static function statutsLibelles(): array
    {
        return [
            self::STATUT_PENDING   => 'En attente',
            self::STATUT_CONFIRMED => 'Confirmé',
            self::STATUT_PAID      => 'Payé',
            self::STATUT_PARTIAL   => 'Partiel',
            self::STATUT_CANCELLED => 'Annulé',
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
        // Gardé pour compatibilité ancienne
        $this->addMediaCollection('preuves')->singleFile();
    }

    public function getPreuveUrlAttribute(): string
    {
        // Récupère la preuve de la dernière déclaration
        $derniere = $this->declarations()->latest()->first();

        return $derniere?->preuve_url ?: $this->getFirstMediaUrl('preuves') ?: '';
    }

    // Relations
    public function declarations(): HasMany
    {
        return $this->hasMany(DeclarationPaiement::class)->latest();
    }

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'projet_id');
    }

    public function contributeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }
}
