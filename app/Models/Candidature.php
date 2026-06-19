<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Candidature extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'offre_emploi_id', 'utilisateur_id', 'lettre_motivation', 'statut', 'note_recruteur',
    ];

    public const STATUT_SOUMISE    = 'soumise';
    public const STATUT_EN_COURS   = 'en_cours';
    public const STATUT_ACCEPTEE   = 'acceptee';
    public const STATUT_REJETEE    = 'rejetee';

    public const STATUTS_LIBELLES = [
        'soumise'   => 'Soumise',
        'en_cours'  => 'En cours d\'examen',
        'acceptee'  => 'Acceptée',
        'rejetee'   => 'Rejetée',
    ];

    // ── Relations ──────────────────────────────────────────

    public function offre(): BelongsTo
    {
        return $this->belongsTo(OffreEmploi::class, 'offre_emploi_id');
    }

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    // ── Media ──────────────────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cv')->singleFile();
    }

    public function getCvUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('cv') ?: null;
    }

    public function getStatutLibelleAttribute(): string
    {
        return self::STATUTS_LIBELLES[$this->statut] ?? $this->statut;
    }
}
