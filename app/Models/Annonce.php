<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Annonce extends Model
{
    use LogsActivity;

    protected $fillable = [
        'auteur_id', 'titre', 'contenu', 'type', 'statut', 'publiee_at', 'expire_at',
    ];

    public const STATUT_BROUILLON = 'brouillon';
    public const STATUT_PUBLIEE   = 'publiee';
    public const STATUT_ARCHIVEE  = 'archivee';

    public const TYPES = [
        'info'    => ['libelle' => 'Information',  'couleur' => 'primary',   'icone' => 'bi-info-circle-fill'],
        'success' => ['libelle' => 'Bonne nouvelle','couleur' => 'success',  'icone' => 'bi-check-circle-fill'],
        'warning' => ['libelle' => 'Attention',    'couleur' => 'warning',   'icone' => 'bi-exclamation-triangle-fill'],
        'urgent'  => ['libelle' => 'Urgent',       'couleur' => 'danger',    'icone' => 'bi-bell-fill'],
    ];

    protected function casts(): array
    {
        return [
            'publiee_at' => 'datetime',
            'expire_at'  => 'datetime',
        ];
    }

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    public function getTypeConfigAttribute(): array
    {
        return self::TYPES[$this->type] ?? self::TYPES['info'];
    }

    public function getEstExpireAttribute(): bool
    {
        return $this->expire_at && $this->expire_at->isPast();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public static function actives()
    {
        return self::where('statut', self::STATUT_PUBLIEE)
            ->where(fn ($q) => $q->whereNull('expire_at')->orWhere('expire_at', '>', now()))
            ->orderByDesc('publiee_at');
    }
}
