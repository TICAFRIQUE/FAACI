<?php

namespace App\Observers;

use App\Models\Valeur;
use Illuminate\Support\Facades\Cache;

class ValeurObserver
{
    public function saved(Valeur $valeur): void
    {
        Cache::forget(Valeur::CACHE_KEY);
    }

    public function deleted(Valeur $valeur): void
    {
        Cache::forget(Valeur::CACHE_KEY);
    }
}
