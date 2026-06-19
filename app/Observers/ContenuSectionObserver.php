<?php

namespace App\Observers;

use App\Models\ContenuSection;
use Illuminate\Support\Facades\Cache;

class ContenuSectionObserver
{
    public function saved(ContenuSection $contenuSection): void
    {
        Cache::forget("contenus.{$contenuSection->groupe}");
    }

    public function deleted(ContenuSection $contenuSection): void
    {
        Cache::forget("contenus.{$contenuSection->groupe}");
    }
}
