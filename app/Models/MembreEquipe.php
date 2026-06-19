<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable(['prenom', 'nom', 'fonction', 'bio', 'ordre', 'actif'])]
class MembreEquipe extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity;

    protected $table = 'membres_equipe';

    public const CACHE_KEY = 'equipe.actifs';

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('photo') ?: null;
    }

    public function getNomCompletAttribute(): string
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    /**
     * Membres actifs de l'équipe dirigeante, pour l'affichage public (mis en cache).
     */
    public static function actifs()
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return self::where('actif', true)->orderBy('ordre')->get();
        });
    }
}
