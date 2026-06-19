<?php

namespace App\Observers;

use App\Models\Activite;
use Illuminate\Support\Facades\Cache;

class ActiviteObserver
{
    public function saved(Activite $activite): void
    {
        Cache::forget(Activite::CACHE_KEY);
    }

    public function deleted(Activite $activite): void
    {
        Cache::forget(Activite::CACHE_KEY);
    }
}
