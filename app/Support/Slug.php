<?php

namespace App\Support;

use Illuminate\Support\Str;

class Slug
{
    /**
     * Génère un slug unique pour un modèle, en ajoutant un suffixe numérique si nécessaire.
     *
     * @param  class-string  $modelClass
     */
    public static function unique(string $modelClass, string $base, ?int $excludeId = null): string
    {
        $slug = Str::slug($base) ?: 'item';
        $original = $slug;
        $i = 2;   

        while (
            $modelClass::where('slug', $slug)
                ->when($excludeId, fn ($query) => $query->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }
}
