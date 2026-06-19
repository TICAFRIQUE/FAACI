<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable(['titre', 'slug', 'extrait', 'contenu', 'categorie', 'statut', 'date_publication'])]
class Article extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity;

    public const STATUT_BROUILLON = 'brouillon';

    public const STATUT_PUBLIE = 'publie';

    public const CACHE_KEY_RECENTS = 'articles.recents';

    protected function casts(): array
    {
        return [
            'date_publication' => 'date',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('photos');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('image') ?: null;
    }

    public function getPhotosAttribute()
    {
        return $this->getMedia('photos');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public static function clearCache(): void
    {
        \Illuminate\Support\Facades\Cache::forget(self::CACHE_KEY_RECENTS);
    }

    public static function recents()
    {
        return Cache::remember(self::CACHE_KEY_RECENTS, 3600, function () {
            return self::where('statut', self::STATUT_PUBLIE)
                ->orderByDesc('date_publication')
                ->take(3)
                ->get();
        });
    }
}
