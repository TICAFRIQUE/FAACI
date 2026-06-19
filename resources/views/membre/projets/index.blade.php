@extends('layouts.app')

@section('title', 'Projets en financement')

@section('breadcrumb')
    <li class="breadcrumb-item active">Projets</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Projets en financement</h4>
        <p class="text-muted small mb-0">{{ $projets->total() }} projet{{ $projets->total() > 1 ? 's' : '' }}</p>
    </div>
    <a href="{{ route('membre.projets.create') }}" class="btn btn-faaci-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Soumettre un projet
    </a>
</div>

{{-- Filtres --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('membre.projets.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-6">
                <input type="text" name="q" class="form-control form-control-sm"
                       placeholder="Rechercher un projet…" value="{{ request('q') }}">
            </div>
            <div class="col-6 col-md-3">
                <select name="type" class="form-select form-select-sm">
                    <option value="">Tous types</option>
                    <option value="fixe" {{ request('type') === 'fixe' ? 'selected' : '' }}>Budget fixe</option>
                    <option value="ouvert" {{ request('type') === 'ouvert' ? 'selected' : '' }}>Budget ouvert</option>
                </select>
            </div>
            <div class="col-6 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-faaci-primary btn-sm flex-grow-1">
                    <i class="bi bi-search me-1"></i> Filtrer
                </button>
                @if (request()->hasAny(['q','type']))
                    <a href="{{ route('membre.projets.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

@if ($projets->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-folder2-open fs-1 d-block mb-2 opacity-25"></i>
        Aucun projet en financement pour le moment.
    </div>
@else
    <div class="row g-3">
        @foreach ($projets as $projet)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('membre.projets.show', $projet) }}"
                   class="card border-0 shadow-sm h-100 text-decoration-none">
                    @if ($projet->image_url)
                        <img src="{{ $projet->image_url }}" alt="{{ $projet->titre }}"
                             class="card-img-top" style="height:180px;object-fit:cover;">
                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center"
                             style="height:180px;background:rgba(13,31,60,0.07);">
                            <i class="bi bi-lightbulb text-faaci-steel" style="font-size:3rem;opacity:0.3;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <h6 class="fw-bold text-dark mb-0">{{ $projet->titre }}</h6>
                            <span class="badge rounded-pill flex-shrink-0"
                                  style="background:rgba(74,127,165,0.12);color:var(--faaci-steel);font-size:0.72rem;">
                                {{ $projet->type_financement === 'fixe' ? 'Fixe' : 'Ouvert' }}
                            </span>
                        </div>
                        @if ($projet->description_courte)
                            <p class="text-muted small mb-3" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $projet->description_courte }}
                            </p>
                        @endif

                        @if ($projet->montant_cible)
                            <div class="mb-1 d-flex justify-content-between small">
                                <span class="text-muted">Collecté</span>
                                <span class="fw-semibold">{{ $projet->pourcentage }} %</span>
                            </div>
                            <div class="progress mb-2" style="height:5px;">
                                <div class="progress-bar"
                                     style="width:{{ $projet->pourcentage }}%;background:var(--faaci-steel);"></div>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>{{ number_format($projet->montant_collecte, 0, ',', ' ') }} FCFA</span>
                                <span>/ {{ number_format($projet->montant_cible, 0, ',', ' ') }} FCFA</span>
                            </div>
                        @else
                            <p class="text-muted small mb-0">
                                {{ number_format($projet->montant_collecte, 0, ',', ' ') }} FCFA collectés
                            </p>
                        @endif
                    </div>
                    <div class="card-footer bg-white border-top-0 pt-0 pb-3 px-3">
                        <div class="d-flex align-items-center gap-2 small text-muted">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:22px;height:22px;background:var(--faaci-navy);color:#fff;font-size:0.65rem;font-weight:700;">
                                {{ mb_strtoupper(mb_substr($projet->porteur->prenom,0,1)) }}{{ mb_strtoupper(mb_substr($projet->porteur->nom,0,1)) }}
                            </div>
                            <span class="text-truncate">{{ $projet->porteur->nom_complet }}</span>
                            @if ($projet->date_fin_financement)
                                <span class="ms-auto flex-shrink-0">
                                    <i class="bi bi-clock me-1"></i>{{ $projet->date_fin_financement->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $projets->links() }}
    </div>
@endif
@endsection
