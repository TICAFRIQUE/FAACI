@extends('layouts.public')

@section('title', 'Nos activités — FAACI')
@section('description', "Découvrez les domaines d'activité de la Fondation AIESEC Alumni Côte d'Ivoire : réseau, financement, emploi et bien plus.")

@section('content')

<section class="page-header">
    <div class="container">
        <div class="page-header-breadcrumb mb-3">
            <a href="{{ route('accueil') }}">Accueil</a> / Activités
        </div>
        <h1 class="page-header-title">Nos activités</h1>
        @if (!empty($contenus['activites_page_soustitre']))
            <p class="page-header-subtitle">{{ $contenus['activites_page_soustitre'] }}</p>
        @else
            <p class="page-header-subtitle">Les domaines dans lesquels la FAACI agit pour ses membres.</p>
        @endif
    </div>
</section>

<section class="section bg-faaci-gray">
    <div class="container">
        @if ($activites->isNotEmpty())
            <div class="row g-4">
                @foreach ($activites as $activite)
                    <div class="col-md-6 col-lg-4 fade-in">
                        <div class="activity-card h-100">
                            <div class="activity-icon"><i class="bi {{ $activite->icone }}"></i></div>
                            <h3 class="activity-title">{{ $activite->titre }}</h3>
                            @if ($activite->description)
                                <div class="activity-desc mb-0 rich-content">{!! $activite->description !!}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-layers fs-1 d-block mb-3 opacity-50"></i>
                <p>Les activités seront prochainement disponibles.</p>
            </div>
        @endif
    </div>
</section>

{{-- CTA Rejoindre --}}
<section class="section bg-white">
    <div class="container text-center">
        <h2 class="section-title">Vous souhaitez participer ?</h2>
        <p class="section-lead mx-auto mb-4" style="max-width:560px;">
            Rejoignez le réseau Alumni AIESEC Côte d'Ivoire et accédez à l'ensemble des services et opportunités de la FAACI.
        </p>
        <a href="{{ route('register') }}" class="btn btn-faaci-navy btn-lg me-2">
            Demander l'adhésion <i class="bi bi-arrow-right ms-1"></i>
        </a>
        <a href="{{ route('contact.send') }}" class="btn btn-outline-secondary btn-lg"
           onclick="event.preventDefault(); document.querySelector('#contact')?.scrollIntoView({behavior:'smooth'})">
            Nous contacter
        </a>
    </div>
</section>

@endsection
