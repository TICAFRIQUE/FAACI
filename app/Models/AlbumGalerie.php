<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable(['titre', 'slug', 'description', 'ordre', 'actif'])]
class AlbumGalerie extends Model
{
    use LogsActivity;

    protected $table = 'albums_galerie';

    public const CACHE_KEY = 'albums_galerie.actifs';

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function images(): HasMany
    {
        return $this->hasMany(ImageGalerie::class, 'album_galerie_id')->orderBy('ordre');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    /**
     * Albums actifs, ordonnés, pour l'affichage public (mis en cache).
     */
    public static function actifs()
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return self::where('actif', true)->with('images')->orderBy('ordre')->get();
        });
    }
}
