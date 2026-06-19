@extends('layouts.public')

@section('title', 'Notre mission — FAACI')
@section('description', "La mission de la Fondation AIESEC Alumni Côte d'Ivoire : renforcer les liens du réseau, partager les opportunités et porter des initiatives à fort impact.")

@section('content')

<section class="page-header">
    <div class="container">
        <div class="page-header-breadcrumb mb-3">
            <a href="{{ route('accueil') }}">Accueil</a> / À propos / Mission
        </div>
        <h1 class="page-header-title">{{ $contenus['mission_titre'] ?? 'Notre mission' }}</h1>
    </div>
</section>

<section class="section bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 fade-in">
                <p class="section-lead">{!! nl2br(e($contenus['mission_contenu'] ?? '')) !!}</p>
            </div>
        </div>
    </div>
</section>

@endsection
