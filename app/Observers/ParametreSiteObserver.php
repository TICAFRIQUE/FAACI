<?php

namespace App\Observers;

use App\Models\ParametreSite;
use Illuminate\Support\Facades\Cache;

class ParametreSiteObserver
{
    public function saved(ParametreSite $parametreSite): void
    {
        Cache::forget(ParametreSite::CACHE_KEY);
    }

    public function deleted(ParametreSite $parametreSite): void
    {
        Cache::forget(ParametreSite::CACHE_KEY);
    }
}
