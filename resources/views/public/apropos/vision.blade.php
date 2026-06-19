@extends('layouts.public')

@section('title', 'Notre vision — FAACI')
@section('description', "La vision de la Fondation AIESEC Alumni Côte d'Ivoire : devenir la référence du réseau Alumni, au service du développement économique du pays et du continent.")

@section('content')

<section class="page-header">
    <div class="container">
        <div class="page-header-breadcrumb mb-3">
            <a href="{{ route('accueil') }}">Accueil</a> / À propos / Vision
        </div>
        <h1 class="page-header-title">{{ $contenus['vision_titre'] ?? 'Notre vision' }}</h1>
    </div>
</section>

<section class="section bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 fade-in">
                <p class="section-lead">{!! nl2br(e($contenus['vision_contenu'] ?? '')) !!}</p>
            </div>
        </div>
    </div>
</section>

@endsection
