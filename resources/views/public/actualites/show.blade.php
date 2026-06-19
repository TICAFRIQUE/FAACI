@extends('layouts.public')

@section('title', $article->titre.' — FAACI')
@section('description', \Illuminate\Support\Str::limit(strip_tags($article->extrait ?: $article->contenu), 160))

@section('content')

<section class="page-header">
    <div class="container">
        <div class="page-header-breadcrumb mb-3">
            <a href="{{ route('accueil') }}">Accueil</a> / <a href="{{ route('actualites.index') }}">Actualités</a> / {{ $article->titre }}
        </div>
        <div class="d-flex align-items-center gap-3 mb-2">
            <span class="news-category">{{ $article->categorie }}</span>
            @if ($article->date_publication)
                <span class="text-white-50 small">{{ $article->date_publication->translatedFormat('j F Y') }}</span>
            @endif
        </div>
        <h1 class="page-header-title">{{ $article->titre }}</h1>
    </div>
</section>

<section class="section bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 fade-in">
                @if ($article->image_url)
                    <img src="{{ $article->image_url }}" alt="{{ $article->titre }}" class="img-fluid rounded-3 mb-4">
                @endif
                <div class="section-lead rich-content">
                    {!! $article->contenu !!}
                </div>

                @if ($article->photos->isNotEmpty())
                    <hr class="my-4">
                    <h3 class="h5 fw-semibold mb-3">Galerie photos</h3>
                    <div class="row g-2">
                        @foreach ($article->photos as $photo)
                            <div class="col-6 col-md-4">
                                <a href="{{ $photo->getUrl() }}" target="_blank" class="d-block">
                                    <img src="{{ $photo->getUrl('thumb') ?: $photo->getUrl() }}"
                                         alt="{{ $article->titre }}"
                                         class="img-fluid rounded-2 w-100"
                                         style="height:180px;object-fit:cover;">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@if ($autres->isNotEmpty())
    <section class="section bg-faaci-gray">
        <div class="container">
            <div class="text-center mb-5 fade-in">
                <span class="section-eyebrow">Vie du réseau</span>
                <h2 class="section-title">Autres actualités</h2>
            </div>
            <div class="row g-4">
                @foreach ($autres as $autre)
                    <div class="col-md-6 col-lg-4 fade-in">
                        @include('public.actualites._card', ['article' => $autre])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection
