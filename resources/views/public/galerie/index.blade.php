@extends('layouts.public')

@section('title', 'Galerie — FAACI')
@section('description', "Les albums photos de la Fondation AIESEC Alumni Côte d'Ivoire : événements, rencontres et moments forts du réseau.")

@section('content')

<section class="page-header">
    <div class="container">
        <div class="page-header-breadcrumb mb-3">
            <a href="{{ route('accueil') }}">Accueil</a> / Galerie
        </div>
        <h1 class="page-header-title">Galerie</h1>
    </div>
</section>

<section class="section bg-white">
    <div class="container">
        @if ($albums->isNotEmpty())
            <div class="row g-4">
                @foreach ($albums as $album)
                    @php
                        $couverture = $album->images->first()?->image_url;
                    @endphp
                    <div class="col-md-6 col-lg-4 fade-in">
                        <a href="{{ route('galerie.show', $album) }}" class="gallery-item" style="background: linear-gradient(135deg, var(--faaci-light), var(--faaci-gray));">
                            @if ($couverture)
                                <img src="{{ $couverture }}" alt="{{ $album->titre }}">
                            @endif
                            <div class="gallery-item-overlay">
                                <div>
                                    <p class="gallery-item-title">{{ $album->titre }}</p>
                                    <span class="gallery-item-count">{{ $album->images->count() }} photo{{ $album->images->count() > 1 ? 's' : '' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted text-center mb-0">Aucun album n'a encore été publié.</p>
        @endif
    </div>
</section>

@endsection
