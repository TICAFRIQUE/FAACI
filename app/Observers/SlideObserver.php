<?php

namespace App\Observers;

use App\Models\Slide;
use Illuminate\Support\Facades\Cache;

class SlideObserver
{
    public function saved(Slide $slide): void
    {
        Cache::forget(Slide::CACHE_KEY);
    }

    public function deleted(Slide $slide): void
    {
        Cache::forget(Slide::CACHE_KEY);
    }
}
