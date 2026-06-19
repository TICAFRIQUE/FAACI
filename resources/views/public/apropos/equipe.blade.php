@extends('layouts.public')

@section('title', 'Équipe dirigeante — FAACI')
@section('description', "L'équipe dirigeante de la Fondation AIESEC Alumni Côte d'Ivoire.")

@section('content')

<section class="page-header">
    <div class="container">
        <div class="page-header-breadcrumb mb-3">
            <a href="{{ route('accueil') }}">Accueil</a> / À propos / Équipe dirigeante
        </div>
        <h1 class="page-header-title">Équipe dirigeante</h1>
    </div>
</section>

<section class="section bg-white">
    <div class="container">
        @if ($membres->isNotEmpty())
            <div class="row g-4">
                @foreach ($membres as $membre)
                    <div class="col-md-6 col-lg-3 fade-in">
                        <div class="team-card">
                            <div class="team-photo">
                                @if ($membre->photo_url)
                                    <img src="{{ $membre->photo_url }}" alt="{{ $membre->nom_complet }}">
                                @else
                                    <i class="bi bi-person"></i>
                                @endif
                            </div>
                            <h3 class="team-name">{{ $membre->nom_complet }}</h3>
                            <p class="team-role">{{ $membre->fonction }}</p>
                            @if ($membre->bio)
                                <div class="team-bio rich-content">{!! $membre->bio !!}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted text-center mb-0">L'équipe dirigeante n'a pas encore été renseignée.</p>
        @endif
    </div>
</section>

@endsection
