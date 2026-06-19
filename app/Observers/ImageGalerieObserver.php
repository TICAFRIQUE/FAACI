<?php

namespace App\Observers;

use App\Models\AlbumGalerie;
use App\Models\ImageGalerie;
use Illuminate\Support\Facades\Cache;

class ImageGalerieObserver
{
    public function saved(ImageGalerie $image): void
    {
        Cache::forget(AlbumGalerie::CACHE_KEY);
    }

    public function deleted(ImageGalerie $image): void
    {
        Cache::forget(AlbumGalerie::CACHE_KEY);
    }
}
