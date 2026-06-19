<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable(['icone', 'titre', 'description', 'ordre', 'actif'])]
class Activite extends Model
{
    use LogsActivity;

    public const CACHE_KEY = 'activites.actives';

    protected function casts(): array
    {
        return ['actif' => 'boolean'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public static function actives()
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return self::where('actif', true)->orderBy('ordre')->get();
        });
    }
}
