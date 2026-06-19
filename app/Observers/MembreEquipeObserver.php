<?php

namespace App\Observers;

use App\Models\MembreEquipe;
use Illuminate\Support\Facades\Cache;

class MembreEquipeObserver
{
    public function saved(MembreEquipe $membreEquipe): void
    {
        Cache::forget(MembreEquipe::CACHE_KEY);
    }

    public function deleted(MembreEquipe $membreEquipe): void
    {
        Cache::forget(MembreEquipe::CACHE_KEY);
    }
}
