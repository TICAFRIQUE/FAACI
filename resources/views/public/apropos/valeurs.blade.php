@extends('layouts.public')

@section('title', 'Nos valeurs — FAACI')
@section('description', "Les valeurs qui guident l'action de la Fondation AIESEC Alumni Côte d'Ivoire et de son réseau.")

@section('content')

<section class="page-header">
    <div class="container">
        <div class="page-header-breadcrumb mb-3">
            <a href="{{ route('accueil') }}">Accueil</a> / À propos / Valeurs
        </div>
        <h1 class="page-header-title">Nos valeurs</h1>
    </div>
</section>

<section class="section bg-white">
    <div class="container">
        @if ($valeurs->isNotEmpty())
            <div class="row g-4">
                @foreach ($valeurs as $valeur)
                    <div class="col-md-6 col-lg-3 fade-in">
                        <div class="activity-card text-center">
                            <div class="activity-icon mx-auto"><i class="bi {{ $valeur->icone }}"></i></div>
                            <h3 class="activity-title">{{ $valeur->titre }}</h3>
                            <div class="activity-desc rich-content">{!! $valeur->description !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted text-center mb-0">Aucune valeur n'a encore été renseignée.</p>
        @endif
    </div>
</section>

@endsection
