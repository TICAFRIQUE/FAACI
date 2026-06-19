@extends('layouts.public')

@section('title', $evenement->titre.' — FAACI')
@section('description', \Illuminate\Support\Str::limit(strip_tags($evenement->description), 160))

@section('content')

@php
    $couleursType = [
        'reunion' => 'rgba(74,127,165,0.95)',
        'pitch' => 'rgba(245,158,11,0.95)',
        'webinaire' => 'rgba(13,31,60,0.95)',
        'networking' => 'rgba(74,127,165,0.95)',
        'ag' => 'rgba(13,31,60,0.95)',
    ];
@endphp

<section class="page-header">
    <div class="container">
        <div class="page-header-breadcrumb mb-3">
            <a href="{{ route('accueil') }}">Accueil</a> / <a href="{{ route('evenements.index') }}">Événements</a> / {{ $evenement->titre }}
        </div>
        <span class="d-inline-block mb-2" style="background: {{ $couleursType[$evenement->type] ?? 'rgba(74,127,165,0.95)' }}; color: #fff; font-size: 0.75rem; font-weight: 500; padding: 0.3rem 0.9rem; border-radius: 999px;">{{ $evenement->type_libelle }}</span>
        <h1 class="page-header-title">{{ $evenement->titre }}</h1>
    </div>
</section>

<section class="section bg-white">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8 fade-in">
                @if ($evenement->image_url)
                    <img src="{{ $evenement->image_url }}" alt="{{ $evenement->titre }}" class="img-fluid rounded-3 mb-4">
                @endif
                <div class="section-lead">
                    {!! $evenement->description !!}
                </div>
            </div>

            <div class="col-lg-4 fade-in">
                <div class="contact-form">
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-calendar-event"></i></div>
                        <div>
                            <p class="contact-info-label">Date</p>
                            <p class="contact-info-value">
                                {{ $evenement->date_debut->translatedFormat('l j F Y') }}
                                @if ($evenement->date_fin && ! $evenement->date_fin->isSameDay($evenement->date_debut))
                                    — {{ $evenement->date_fin->translatedFormat('l j F Y') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-clock"></i></div>
                        <div>
                            <p class="contact-info-label">Heure</p>
                            <p class="contact-info-value">
                                {{ $evenement->date_debut->format('H\hi') }}
                                @if ($evenement->date_fin)
                                    — {{ $evenement->date_fin->format('H\hi') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @if ($evenement->lieu)
                        <div class="contact-info-item">
                            <div class="contact-info-icon"><i class="bi bi-geo-alt"></i></div>
                            <div>
                                <p class="contact-info-label">Lieu</p>
                                <p class="contact-info-value">{{ $evenement->lieu }}</p>
                            </div>
                        </div>
                    @endif
                    @if ($evenement->lien_visio)
                        <div class="contact-info-item">
                            <div class="contact-info-icon"><i class="bi bi-camera-video"></i></div>
                            <div>
                                <p class="contact-info-label">Format</p>
                                <p class="contact-info-value">En ligne — lien transmis aux membres inscrits</p>
                            </div>
                        </div>
                    @endif

                    <hr>

                    <p class="contact-info-value mb-3">Cet événement fait partie du calendrier du réseau FAACI. Devenez membre pour vous y inscrire et accéder à tous les événements réservés.</p>
                    <a href="{{ route('register') }}" class="btn btn-faaci-navy w-100">Devenir membre</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
