@extends('layouts.app')

@section('title', 'Annuaire des entreprises Alumni')

@section('breadcrumb')
    <li class="breadcrumb-item active">Entreprises Alumni</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Annuaire des entreprises Alumni</h4>
        <p class="text-muted small mb-0">{{ $entreprises->total() }} entreprise{{ $entreprises->total() > 1 ? 's' : '' }}</p>
    </div>
    <a href="{{ route('membre.entreprises.create') }}" class="btn btn-faaci-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Référencer mon entreprise
    </a>
</div>

{{-- Filtres --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('membre.entreprises.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <input type="text" name="q" class="form-control form-control-sm"
                       placeholder="Nom, secteur, localisation…"
                       value="{{ request('q') }}">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label small text-muted mb-1">Secteur</label>
                <select name="secteur" class="form-select form-select-sm">
                    <option value="">Tous les secteurs</option>
                    @foreach ($secteurs as $s)
                        <option value="{{ $s }}" {{ request('secteur') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-faaci-primary btn-sm flex-grow-1">
                    <i class="bi bi-search me-1"></i> Filtrer
                </button>
                @if (request()->hasAny(['q', 'secteur']))
                    <a href="{{ route('membre.entreprises.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

@if ($entreprises->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-building fs-1 d-block mb-2 opacity-25"></i>
        Aucune entreprise ne correspond à votre recherche.
        <div class="mt-3">
            <a href="{{ route('membre.entreprises.create') }}" class="btn btn-faaci-primary btn-sm">
                Référencer mon entreprise
            </a>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach ($entreprises as $e)
            <div class="col-sm-6 col-lg-4">
                <a href="{{ route('membre.entreprises.show', $e) }}"
                   class="card border-0 shadow-sm h-100 text-decoration-none">
                    <div class="card-body d-flex gap-3 align-items-start">
                        {{-- Logo --}}
                        @if ($e->logo_url)
                            <img src="{{ $e->logo_url }}" alt="{{ $e->nom }}"
                                 class="rounded flex-shrink-0"
                                 style="width:56px;height:56px;object-fit:cover;">
                        @else
                            <div class="rounded flex-shrink-0 d-flex align-items-center justify-content-center"
                                 style="width:56px;height:56px;background:var(--faaci-navy);color:#fff;font-size:1.4rem;">
                                <i class="bi bi-building"></i>
                            </div>
                        @endif

                        <div class="min-w-0">
                            <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $e->nom }}</h6>
                            @if ($e->secteur)
                                <p class="text-muted small mb-1 text-truncate">{{ $e->secteur }}</p>
                            @endif
                            @if ($e->localisation)
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $e->localisation }}
                                </p>
                            @endif
                            @if ($e->proprietaire)
                                <p class="small mb-0" style="color:var(--faaci-steel);">
                                    <i class="bi bi-person me-1"></i>{{ $e->proprietaire->nom_complet }}
                                </p>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $entreprises->links() }}
    </div>
@endif
@endsection
