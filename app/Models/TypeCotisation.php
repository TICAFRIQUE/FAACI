<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeCotisation extends Model
{
    protected $table = 'types_cotisation';

    protected $fillable = ['nom', 'frequence', 'montant_standard', 'description', 'actif', 'date_debut', 'date_fin'];

    protected function casts(): array
    {
        return [
            'montant_standard' => 'decimal:2',
            'actif'            => 'boolean',
            'date_debut'       => 'date',
            'date_fin'         => 'date',
        ];
    }

    public static function frequences(): array
    {
        return [
            'journaliere'  => 'Journalière',
            'hebdomadaire' => 'Hebdomadaire',
            'mensuelle'    => 'Mensuelle',
            'semestrielle' => 'Semestrielle',
            'annuelle'     => 'Annuelle',
            'autre'        => 'Autre',
        ];
    }

    public static function actif()
    {
        return static::where('actif', true)->orderBy('nom');
    }

    public function periodes(): HasMany
    {
        return $this->hasMany(PeriodeCotisation::class, 'type_cotisation_id')->orderBy('date_debut');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(PaiementCotisation::class, 'type_cotisation_id');
    }
}
