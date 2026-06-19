<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InscriptionEvenement extends Model
{
    protected $table = 'inscriptions_evenements';

    protected $fillable = ['evenement_id', 'utilisateur_id', 'statut'];

    public const STATUT_INSCRIT  = 'inscrit';
    public const STATUT_CONFIRME = 'confirme';
    public const STATUT_ANNULE   = 'annule';

    public function evenement(): BelongsTo
    {
        return $this->belongsTo(Evenement::class, 'evenement_id');
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
