<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidatureCompetition extends Model
{
    protected $table = 'candidatures_competition';

    protected $fillable = [
        'competition_id',
        'utilisateur_id',
        'titre_projet',
        'resume_projet',
        'statut',
        'note_jury',
    ];

    public const STATUT_EN_ATTENTE   = 'en_attente';
    public const STATUT_SELECTIONNEE = 'selectionnee';
    public const STATUT_ELIMINEE     = 'eliminee';
    public const STATUT_GAGNANTE     = 'gagnante';

    public static function statuts(): array
    {
        return [
            self::STATUT_EN_ATTENTE   => 'En attente',
            self::STATUT_SELECTIONNEE => 'Sélectionnée',
            self::STATUT_ELIMINEE     => 'Éliminée',
            self::STATUT_GAGNANTE     => 'Gagnante',
        ];
    }

    public static function statutsBadge(): array
    {
        return [
            self::STATUT_EN_ATTENTE   => 'secondary',
            self::STATUT_SELECTIONNEE => 'info',
            self::STATUT_ELIMINEE     => 'danger',
            self::STATUT_GAGNANTE     => 'success',
        ];
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class, 'competition_id');
    }

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
