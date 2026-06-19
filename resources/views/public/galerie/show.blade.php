@extends('layouts.public')

@section('title', $album->titre.' — Galerie — FAACI')
@section('description', \Illuminate\Support\Str::limit(strip_tags($album->description ?: $album->titre), 160))

@section('content')

<section class="page-header">
    <div class="container">
        <div class="page-header-breadcrumb mb-3">
            <a href="{{ route('accueil') }}">Accueil</a> / <a href="{{ route('galerie.index') }}">Galerie</a> / {{ $album->titre }}
        </div>
        <h1 class="page-header-title">{{ $album->titre }}</h1>
        @if ($album->description)
            <p class="mb-0" style="color: rgba(255,255,255,0.7); max-width: 640px;">{{ $album->description }}</p>
        @endif
    </div>
</section>

<section class="section bg-white">
    <div class="container">
        @if ($album->images->isNotEmpty())
            <div class="row g-3">
                @foreach ($album->images as $image)
                    @if ($image->image_url)
                        <div class="col-6 col-md-4 col-lg-3 fade-in">
                            <a href="{{ $image->image_url }}" target="_blank" rel="noopener" class="gallery-item">
                                <img src="{{ $image->image_url }}" alt="{{ $image->legende ?: $album->titre }}">
                                @if ($image->legende)
                                    <div class="gallery-item-overlay">
                                        <p class="gallery-item-title">{{ $image->legende }}</p>
                                    </div>
                                @endif
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <p class="text-muted text-center mb-0">Cet album ne contient pas encore de photos.</p>
        @endif

        <div class="text-center mt-5">
            <a href="{{ route('galerie.index') }}" class="text-decoration-none text-faaci-navy fw-semibold"><i class="bi bi-arrow-left me-1"></i> Retour à la galerie</a>
        </div>
    </div>
</section>

@endsection
