<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Evenement extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'titre', 'slug', 'description', 'type',
        'date_debut', 'date_fin', 'lieu', 'lien_visio',
        'statut', 'est_public', 'capacite_max', 'organisateur_id',
    ];

    public const STATUT_BROUILLON = 'brouillon';
    public const STATUT_PUBLIE    = 'publie';

    public const TYPES = [
        'reunion'    => 'Réunion',
        'pitch'      => 'Pitch',
        'webinaire'  => 'Webinaire',
        'networking' => 'Networking',
        'ag'         => 'Assemblée générale',
    ];

    public const CACHE_KEY_PROCHAINS = 'evenements.prochains';

    protected function casts(): array
    {
        return [
            'date_debut' => 'datetime',
            'date_fin'   => 'datetime',
            'est_public' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ── Relations ──────────────────────────────────────────

    public function organisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organisateur_id');
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(InscriptionEvenement::class, 'evenement_id');
    }

    // ── Accesseurs ─────────────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('image') ?: null;
    }

    public function getTypeLibelleAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getNbInscritsAttribute(): int
    {
        return $this->inscriptions()->whereIn('statut', ['inscrit', 'confirme'])->count();
    }

    public function getEstCompletAttribute(): bool
    {
        if (! $this->capacite_max) {
            return false;
        }

        return $this->nb_inscrits >= $this->capacite_max;
    }

    public function getEstPasseAttribute(): bool
    {
        return $this->date_debut->isPast();
    }

    // ── Scopes / Helpers ───────────────────────────────────

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public static function prochains(bool $publicSeulement = true)
    {
        $cacheKey = $publicSeulement ? self::CACHE_KEY_PROCHAINS : 'evenements.prochains_membres';

        return Cache::remember($cacheKey, 300, function () use ($publicSeulement) {
            $q = self::where('statut', self::STATUT_PUBLIE)
                ->where('date_debut', '>=', now()->startOfDay())
                ->orderBy('date_debut');

            if ($publicSeulement) {
                $q->where('est_public', true);
            }

            return $q->take(3)->get();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY_PROCHAINS);
        Cache::forget('evenements.prochains_membres');
    }
}
