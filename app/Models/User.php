<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['prenom', 'nom', 'email', 'telephone', 'password', 'statut', 'bio', 'promotion_aiesec', 'comite_local', 'secteur', 'ville', 'competences', 'annonces_lues_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, InteractsWithMedia, LogsActivity, Notifiable;

    public const STATUT_EN_ATTENTE = 'en_attente';

    public const STATUT_ACTIF = 'actif';

    public const STATUT_SUSPENDU = 'suspendu';

    public const STATUT_INACTIF = 'inactif';

    public const STATUT_REJETE = 'rejete';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'password'           => 'hashed',
            'date_validation'    => 'datetime',
            'date_suspension'    => 'datetime',
            'date_inactivation'  => 'datetime',
            'competences'        => 'array',
            'annonces_lues_at'   => 'datetime',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos')->singleFile();
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('photos') ?: '';
    }

    public function getCompletenessAttribute(): int
    {
        $champs = ['prenom', 'nom', 'email', 'telephone', 'bio', 'promotion_aiesec', 'comite_local', 'secteur', 'ville'];
        $remplis = collect($champs)->filter(fn ($c) => filled($this->$c))->count();
        $photo   = $this->getFirstMediaUrl('photos') ? 1 : 0;
        $comps   = filled($this->competences) ? 1 : 0;

        return (int) round(($remplis + $photo + $comps) / (count($champs) + 2) * 100);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['prenom', 'nom', 'email', 'telephone', 'statut', 'motif_rejet', 'motif_suspension'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function investissements(): HasMany
    {
        return $this->hasMany(Contribution::class, 'utilisateur_id');
    }

    public function dons(): HasMany
    {
        return $this->hasMany(Don::class, 'utilisateur_id');
    }

    public function entreprises(): HasMany
    {
        return $this->hasMany(Entreprise::class, 'utilisateur_id');
    }

    public function projets(): HasMany
    {
        return $this->hasMany(Projet::class, 'utilisateur_id');
    }

    public function offresEmploi(): HasMany
    {
        return $this->hasMany(OffreEmploi::class, 'utilisateur_id');
    }

    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class, 'utilisateur_id');
    }

    /**
     * Administrateur ayant validé ce membre.
     */
    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function getNomCompletAttribute(): string
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    public function estActif(): bool
    {
        return $this->statut === self::STATUT_ACTIF;
    }

    public function sendPasswordResetNotification($token): void
    {
        $lien = route('password.reset', ['token' => $token, 'email' => $this->email]);
        \Illuminate\Support\Facades\Mail::to($this->email)
            ->queue(new \App\Mail\LienReinitialisationMotDePasse($this, $lien));
    }
}
