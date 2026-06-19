<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable([
    'titre', 'sous_titre', 'description',
    'libelle_bouton_1', 'lien_bouton_1',
    'libelle_bouton_2', 'lien_bouton_2',
    'ordre', 'actif',
])]
class Slide extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity;

    public const CACHE_KEY = 'slides.actives';

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('image') ?: null;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    /**
     * Slides actives, ordonnées, pour l'affichage public (mis en cache).
     */
    public static function actives()
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return self::where('actif', true)->orderBy('ordre')->get();
        });
    }
}
