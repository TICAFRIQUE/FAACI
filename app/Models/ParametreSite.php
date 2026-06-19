<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable(['cle', 'libelle', 'valeur', 'ordre'])]
class ParametreSite extends Model
{
    use LogsActivity;

    protected $table = 'parametres_site';

    public const CACHE_KEY = 'parametres.site';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    /**
     * Récupère un paramètre du site par sa clé, pour l'affichage public (mis en cache).
     */
    public static function valeur(string $cle, ?string $defaut = null): ?string
    {
        return self::tous()[$cle] ?? $defaut;
    }

    /**
     * Tous les paramètres du site, indexés par clé (mis en cache).
     */
    public static function tous(): array
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return self::pluck('valeur', 'cle')->all();
        });
    }
}
