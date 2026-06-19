<?php

namespace App\Observers;

use App\Models\AlbumGalerie;
use App\Support\Slug;
use Illuminate\Support\Facades\Cache;

class AlbumGalerieObserver
{
    public function creating(AlbumGalerie $album): void
    {
        $album->slug = Slug::unique(AlbumGalerie::class, $album->slug ?: $album->titre);
    }

    public function updating(AlbumGalerie $album): void
    {
        if ($album->isDirty('slug')) {
            $album->slug = Slug::unique(AlbumGalerie::class, $album->slug, $album->id);
        }
    }

    public function saved(AlbumGalerie $album): void
    {
        Cache::forget(AlbumGalerie::CACHE_KEY);
    }

    public function deleted(AlbumGalerie $album): void
    {
        Cache::forget(AlbumGalerie::CACHE_KEY);
    }
}
