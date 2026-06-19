<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OffreEmploi extends Model
{
    use LogsActivity;

    protected $table = 'offres_emploi';

    protected $fillable = [
        'utilisateur_id', 'titre', 'slug', 'description',
        'type_contrat', 'localisation', 'salaire',
        'competences_requises', 'lien_externe', 'date_expiration',
        'statut', 'valide_par', 'date_validation', 'motif_rejet',
    ];

    public const STATUT_BROUILLON  = 'brouillon';
    public const STATUT_EN_ATTENTE = 'en_attente';
    public const STATUT_ACTIVE     = 'active';
    public const STATUT_EXPIREE    = 'expiree';
    public const STATUT_REJETEE    = 'rejetee';

    public const TYPES_CONTRAT = [
        'cdi'         => 'CDI',
        'cdd'         => 'CDD',
        'stage'       => 'Stage',
        'freelance'   => 'Freelance',
        'alternance'  => 'Alternance',
    ];

    public const STATUTS_LIBELLES = [
        'brouillon'  => 'Brouillon',
        'en_attente' => 'En attente',
        'active'     => 'Active',
        'expiree'    => 'Expirée',
        'rejetee'    => 'Rejetée',
    ];

    protected function casts(): array
    {
        return [
            'date_expiration' => 'date',
            'date_validation' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ── Relations ──────────────────────────────────────────

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class, 'offre_emploi_id')->latest();
    }

    // ── Accesseurs ─────────────────────────────────────────

    public function getTypeLibelleAttribute(): string
    {
        return self::TYPES_CONTRAT[$this->type_contrat] ?? $this->type_contrat;
    }

    public function getStatutLibelleAttribute(): string
    {
        return self::STATUTS_LIBELLES[$this->statut] ?? $this->statut;
    }

    public function getEstExpireeAttribute(): bool
    {
        return $this->date_expiration && $this->date_expiration->isPast();
    }

    // ── Helpers ────────────────────────────────────────────

    public static function uniqueSlug(string $titre): string
    {
        $base = Str::slug($titre);
        $slug = $base;
        $i    = 1;
        while (self::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
