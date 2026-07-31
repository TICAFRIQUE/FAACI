@extends('layouts.app')

@section('title', $evenement->titre)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.evenements.index') }}">Événements</a></li>
    <li class="breadcrumb-item active text-truncate" style="max-width:200px;">{{ $evenement->titre }}</li>
@endsection

@section('content')
<div class="mb-3">
    <a href="{{ route('membre.evenements.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

@php
    $passe   = $evenement->est_passe;
    $complet = $evenement->est_complet;
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        @if ($evenement->image_url)
            <img src="{{ $evenement->image_url }}" alt="" class="img-fluid rounded-3 mb-4 w-100"
                 style="max-height:320px;object-fit:cover;">
        @endif

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-2 mb-2">
                    <h4 class="fw-bold flex-grow-1 mb-0">{{ $evenement->titre }}</h4>
                    <span class="badge bg-light text-dark border flex-shrink-0">{{ $evenement->type_libelle }}</span>
                </div>

                <div class="d-flex flex-wrap gap-3 small text-muted mb-4">
                    <span><i class="bi bi-calendar me-1"></i>{{ $evenement->date_debut->translatedFormat('d F Y à H:i') }}</span>
                    @if ($evenement->date_fin)
                        <span><i class="bi bi-calendar-check me-1"></i>Fin : {{ $evenement->date_fin->translatedFormat('d F Y à H:i') }}</span>
                    @endif
                    @if ($evenement->lieu)
                        <span><i class="bi bi-geo-alt me-1"></i>{{ $evenement->lieu }}</span>
                    @endif
                    @if ($evenement->lien_visio)
                        <a href="{{ $evenement->lien_visio }}" target="_blank" class="text-faaci-steel">
                            <i class="bi bi-camera-video me-1"></i>Rejoindre en ligne
                        </a>
                    @endif
                </div>

                <div class="rich-content">{!! $evenement->description !!}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                {{-- Capacité --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small text-muted fw-semibold">Inscrits</span>
                    <span class="fw-bold">
                        {{ $nbInscrits }}
                        @if ($evenement->capacite_max)
                            <span class="text-muted fw-normal">/ {{ $evenement->capacite_max }}</span>
                        @endif
                    </span>
                </div>
                @if ($evenement->capacite_max)
                    @php $pct = min(100, round($nbInscrits / $evenement->capacite_max * 100)); @endphp
                    <div class="progress mb-3" style="height:6px;">
                        <div class="progress-bar {{ $complet ? 'bg-danger' : '' }}" style="width:{{ $pct }}%;"></div>
                    </div>
                @endif

                {{-- Bouton inscription --}}
                @if ($passe)
                    <div class="alert alert-secondary text-center mb-0 small">Cet événement est passé.</div>
                @elseif ($monInscription && $monInscription->statut !== 'annule')
                    <div class="alert alert-success text-center mb-2 small">
                        <i class="bi bi-check-circle me-1"></i> Vous êtes inscrit(e) !
                    </div>
                    <form method="POST" action="{{ route('membre.evenements.desinscrire', $evenement) }}"
                          data-confirm="Se désinscrire de cet événement ?">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 btn-sm">
                            Se désinscrire
                        </button>
                    </form>
                @elseif ($complet)
                    <div class="alert alert-danger text-center mb-0 small">Capacité maximale atteinte.</div>
                @else
                    <form method="POST" action="{{ route('membre.evenements.inscrire', $evenement) }}">
                        @csrf
                        <button type="submit" class="btn btn-faaci-primary w-100">
                            <i class="bi bi-calendar-check me-1"></i> S'inscrire
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if ($evenement->organisateur)
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <p class="small text-muted mb-2 fw-semibold">Organisateur</p>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 text-white fw-bold"
                             style="width:34px;height:34px;background:var(--faaci-navy);font-size:0.75rem;">
                            {{ mb_strtoupper(mb_substr($evenement->organisateur->prenom,0,1)) }}{{ mb_strtoupper(mb_substr($evenement->organisateur->nom,0,1)) }}
                        </div>
                        <div class="small fw-medium">{{ $evenement->organisateur->nom_complet }}</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
