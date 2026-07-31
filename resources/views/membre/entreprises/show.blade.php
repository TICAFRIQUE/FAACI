@extends('layouts.app')

@section('title', $entreprise->nom)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.entreprises.index') }}">Entreprises Alumni</a></li>
    <li class="breadcrumb-item active">{{ $entreprise->nom }}</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-4">
                @if ($entreprise->logo_url)
                    <img src="{{ $entreprise->logo_url }}" alt="{{ $entreprise->nom }}"
                         class="rounded mb-3"
                         style="width:100px;height:100px;object-fit:cover;">
                @else
                    <div class="rounded d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:100px;height:100px;background:var(--faaci-navy);color:#fff;font-size:2.5rem;">
                        <i class="bi bi-building"></i>
                    </div>
                @endif

                <h6 class="fw-bold mb-1">{{ $entreprise->nom }}</h6>
                @if ($entreprise->secteur)
                    <p class="text-muted small mb-1">{{ $entreprise->secteur }}</p>
                @endif
                @if ($entreprise->localisation)
                    <p class="text-muted small mb-0">
                        <i class="bi bi-geo-alt me-1"></i>{{ $entreprise->localisation }}
                    </p>
                @endif

                @if ($entreprise->utilisateur_id === auth()->id())
                    <a href="{{ route('membre.entreprises.edit', $entreprise) }}" class="btn btn-outline-primary btn-sm mt-3 w-100">
                        <i class="bi bi-pencil me-1"></i> Modifier
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8 col-lg-9">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold border-bottom-0 pt-3">
                <i class="bi bi-info-circle me-1 text-faaci-steel"></i> À propos
            </div>
            <div class="card-body pt-0">
                @if ($entreprise->description)
                    <p class="mb-3">{{ $entreprise->description }}</p>
                @else
                    <p class="text-muted small mb-3">Aucune description fournie.</p>
                @endif

                <dl class="row mb-0">
                    @if ($entreprise->annee_creation)
                        <dt class="col-sm-4 text-muted fw-normal small">Fondée en</dt>
                        <dd class="col-sm-8 fw-medium">{{ $entreprise->annee_creation }}</dd>
                    @endif

                    @if ($entreprise->telephone)
                        <dt class="col-sm-4 text-muted fw-normal small">Téléphone</dt>
                        <dd class="col-sm-8">
                            <a href="tel:{{ $entreprise->telephone }}" class="text-decoration-none">{{ $entreprise->telephone }}</a>
                        </dd>
                    @endif

                    @if ($entreprise->email_contact)
                        <dt class="col-sm-4 text-muted fw-normal small">E-mail</dt>
                        <dd class="col-sm-8">
                            <a href="mailto:{{ $entreprise->email_contact }}" class="text-decoration-none">{{ $entreprise->email_contact }}</a>
                        </dd>
                    @endif

                    @if ($entreprise->site_web)
                        <dt class="col-sm-4 text-muted fw-normal small">Site web</dt>
                        <dd class="col-sm-8">
                            <a href="{{ $entreprise->site_web }}" target="_blank" rel="noopener" class="text-decoration-none">
                                {{ $entreprise->site_web }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                            </a>
                        </dd>
                    @endif

                    <dt class="col-sm-4 text-muted fw-normal small">Membre FAACI</dt>
                    <dd class="col-sm-8 fw-medium">{{ $entreprise->proprietaire?->nom_complet ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
