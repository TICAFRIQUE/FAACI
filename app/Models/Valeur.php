<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable(['icone', 'titre', 'description', 'ordre'])]
class Valeur extends Model
{
    use LogsActivity;

    public const CACHE_KEY = 'valeurs.toutes';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    /**
     * Valeurs ordonnées, pour l'affichage public (mis en cache).
     */
    public static function toutes()
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return self::orderBy('ordre')->get();
        });
    }
}
