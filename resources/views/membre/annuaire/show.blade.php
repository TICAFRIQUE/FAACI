@extends('layouts.app')

@section('title', $membre->nom_complet)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.annuaire') }}">Annuaire</a></li>
    <li class="breadcrumb-item active">{{ $membre->nom_complet }}</li>
@endsection

@section('content')
<div class="mb-3">
    <a href="{{ route('membre.annuaire') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour à l'annuaire
    </a>
</div>

<div class="row g-4">
    {{-- Carte profil --}}
    <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-4">
                @if ($membre->photo_url)
                    <img src="{{ $membre->photo_url }}" alt="{{ $membre->nom_complet }}"
                         class="rounded-circle mb-3 shadow-sm"
                         style="width:100px;height:100px;object-fit:cover;">
                @else
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:100px;height:100px;background:var(--faaci-navy);color:#fff;font-size:2rem;font-weight:700;">
                        {{ mb_strtoupper(mb_substr($membre->prenom,0,1)) }}{{ mb_strtoupper(mb_substr($membre->nom,0,1)) }}
                    </div>
                @endif

                <h5 class="fw-bold mb-0">{{ $membre->nom_complet }}</h5>
                @if ($membre->secteur)
                    <p class="text-muted small mb-1">{{ $membre->secteur }}</p>
                @endif
                @if ($membre->ville)
                    <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i>{{ $membre->ville }}</p>
                @endif
                @if ($membre->promotion_aiesec)
                    <span class="badge rounded-pill mt-2 d-inline-block"
                          style="background:rgba(74,127,165,0.12);color:var(--faaci-steel);">
                        Promo {{ $membre->promotion_aiesec }}
                    </span>
                @endif

                {{-- Contact --}}
                <div class="mt-3 d-grid gap-2">
                    @if ($membre->email)
                        <a href="mailto:{{ $membre->email }}" class="btn btn-faaci-primary btn-sm">
                            <i class="bi bi-envelope me-1"></i> Envoyer un e-mail
                        </a>
                    @endif
                    @if ($membre->telephone)
                        <a href="tel:{{ $membre->telephone }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-telephone me-1"></i> {{ $membre->telephone }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Infos détaillées --}}
    <div class="col-md-8 col-lg-9">
        @if ($membre->bio)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold border-bottom-0 pt-3">
                    <i class="bi bi-file-text me-1 text-faaci-steel"></i> À propos
                </div>
                <div class="card-body pt-0">
                    <div class="rich-content mb-0">{!! $membre->bio !!}</div>
                </div>
            </div>
        @endif

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold border-bottom-0 pt-3">
                <i class="bi bi-info-circle me-1 text-faaci-steel"></i> Informations
            </div>
            <div class="card-body pt-0">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted fw-normal small">Secteur</dt>
                    <dd class="col-sm-8 fw-medium">{{ $membre->secteur ?: '—' }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Ville</dt>
                    <dd class="col-sm-8 fw-medium">{{ $membre->ville ?: '—' }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Promotion</dt>
                    <dd class="col-sm-8 fw-medium">{{ $membre->promotion_aiesec ?: '—' }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Comité local</dt>
                    <dd class="col-sm-8 fw-medium">{{ $membre->comite_local ?: '—' }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Membre depuis</dt>
                    <dd class="col-sm-8 fw-medium">{{ $membre->date_validation?->translatedFormat('F Y') ?? '—' }}</dd>
                </dl>
            </div>
        </div>

        @if ($membre->competences)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold border-bottom-0 pt-3">
                    <i class="bi bi-tags me-1 text-faaci-steel"></i> Compétences
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($membre->competences as $comp)
                            <span class="badge rounded-pill"
                                  style="background:rgba(74,127,165,0.12);color:var(--faaci-steel);font-weight:500;font-size:0.82rem;">
                                {{ $comp }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
