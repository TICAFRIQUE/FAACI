@extends('layouts.public')

@section('title', 'Notre histoire — FAACI')
@section('description', "L'histoire de la Fondation AIESEC Alumni Côte d'Ivoire, née de la volonté d'anciens membres d'AIESEC de garder vivant l'esprit du réseau.")

@section('content')

<section class="page-header">
    <div class="container">
        <div class="page-header-breadcrumb mb-3">
            <a href="{{ route('accueil') }}">Accueil</a> / À propos / Histoire
        </div>
        <h1 class="page-header-title">{{ $contenus['histoire_titre'] ?? 'Notre histoire' }}</h1>
    </div>
</section>

<section class="section bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 fade-in">
                <p class="section-lead">{!! nl2br(e($contenus['histoire_contenu'] ?? '')) !!}</p>
            </div>
        </div>
    </div>
</section>

@endsection
