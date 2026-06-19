<?php

namespace App\Observers;

use App\Models\Article;
use App\Support\Slug;
use Illuminate\Support\Facades\Cache;

class ArticleObserver
{
    public function creating(Article $article): void
    {
        $article->slug = Slug::unique(Article::class, $article->slug ?: $article->titre);
    }

    public function updating(Article $article): void
    {
        if ($article->isDirty('slug')) {
            $article->slug = Slug::unique(Article::class, $article->slug, $article->id);
        }
    }

    public function saved(Article $article): void
    {
        Cache::forget(Article::CACHE_KEY_RECENTS);
    }

    public function deleted(Article $article): void
    {
        Cache::forget(Article::CACHE_KEY_RECENTS);
    }
}
