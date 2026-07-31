<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competition extends Model
{
    protected $table = 'competitions';

    protected $fillable = [
        'titre',
        'description',
        'budget',
        'date_limite_candidature',
        'date_pitch',
        'statut',
        'cree_par',
    ];

    protected function casts(): array
    {
        return [
            'budget'                  => 'decimal:2',
            'date_limite_candidature' => 'date',
            'date_pitch'              => 'date',
        ];
    }

    public const STATUT_BROUILLON = 'brouillon';
    public const STATUT_OUVERTE   = 'ouverte';
    public const STATUT_CLOTUREE  = 'cloturee';
    public const STATUT_TERMINEE  = 'terminee';

    public static function statuts(): array
    {
        return [
            self::STATUT_BROUILLON => 'Brouillon',
            self::STATUT_OUVERTE   => 'Ouverte',
            self::STATUT_CLOTUREE  => 'Clôturée',
            self::STATUT_TERMINEE  => 'Terminée',
        ];
    }

    public static function statutsBadge(): array
    {
        return [
            self::STATUT_BROUILLON => 'secondary',
            self::STATUT_OUVERTE   => 'success',
            self::STATUT_CLOTUREE  => 'warning',
            self::STATUT_TERMINEE  => 'dark',
        ];
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function candidatures(): HasMany
    {
        return $this->hasMany(CandidatureCompetition::class, 'competition_id');
    }

    public function estOuverte(): bool
    {
        if ($this->statut !== self::STATUT_OUVERTE) {
            return false;
        }
        if ($this->date_limite_candidature && $this->date_limite_candidature->isPast()) {
            return false;
        }

        return true;
    }
}
