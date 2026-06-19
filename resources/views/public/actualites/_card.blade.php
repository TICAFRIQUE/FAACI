<article class="news-card">
    <div class="news-image" @if ($article->image_url) style="background-image: url('{{ $article->image_url }}'); background-size: cover; background-position: center;" @endif>
        @unless ($article->image_url)
            <i class="bi bi-image"></i>
        @endunless
    </div>
    <div class="news-body">
        <div class="news-meta">
            <span class="news-category">{{ $article->categorie }}</span>
            @if ($article->date_publication)
                <span class="news-date">{{ $article->date_publication->translatedFormat('j F Y') }}</span>
            @endif
        </div>
        <h3 class="news-title"><a href="{{ route('actualites.show', $article) }}">{{ $article->titre }}</a></h3>
        <p class="news-excerpt line-clamp-2">{{ strip_tags($article->extrait) }}</p>
    </div>
</article>
