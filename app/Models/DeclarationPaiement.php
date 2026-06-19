<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class DeclarationPaiement extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'declarations_paiement';

    protected $fillable = [
        'contribution_id',
        'montant',
        'moyen_paiement',
        'note',
        'valide_par',
        'date_validation',
    ];

    protected function casts(): array
    {
        return [
            'montant'          => 'decimal:2',
            'date_validation'  => 'datetime',
        ];
    }

    public static function moyensPaiement(): array
    {
        return Contribution::moyensPaiement();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('preuves')->singleFile();
    }

    public function getPreuveUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('preuves') ?: '';
    }

    public function contribution(): BelongsTo
    {
        return $this->belongsTo(Contribution::class);
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }
}
