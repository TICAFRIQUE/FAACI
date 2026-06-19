<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable(['groupe', 'cle', 'libelle', 'type', 'valeur', 'ordre'])]
class ContenuSection extends Model
{
    use LogsActivity;

    protected $table = 'contenus_sections';

    public const TYPE_TEXTE = 'texte';

    public const TYPE_TEXTAREA = 'textarea';

    public const TYPE_RICHTEXT = 'richtext';

    public const TYPE_NOMBRE = 'nombre';

    public const TYPE_IMAGE = 'image';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    /**
     * Valeurs des contenus d'un groupe, indexées par clé, pour l'affichage public (mis en cache).
     */
    public static function pourGroupe(string $groupe): array
    {
        return Cache::remember("contenus.{$groupe}", 3600, function () use ($groupe) {
            return self::where('groupe', $groupe)->orderBy('ordre')->pluck('valeur', 'cle')->all();
        });
    }
}
