@extends('layouts.app')

@section('title', 'Annuaire des membres')

@section('breadcrumb')
    <li class="breadcrumb-item active">Annuaire</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Annuaire des membres</h4>
        <p class="text-muted small mb-0">{{ $membres->total() }} membre{{ $membres->total() > 1 ? 's' : '' }} actif{{ $membres->total() > 1 ? 's' : '' }}</p>
    </div>
</div>

{{-- Filtres --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('membre.annuaire') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Nom, secteur, ville…"
                       value="{{ request('q') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small text-muted mb-1">Secteur</label>
                <select name="secteur" class="form-select form-select-sm">
                    <option value="">Tous</option>
                    @foreach ($secteurs as $s)
                        <option value="{{ $s }}" {{ request('secteur') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small text-muted mb-1">Ville</label>
                <select name="ville" class="form-select form-select-sm">
                    <option value="">Toutes</option>
                    @foreach ($villes as $v)
                        <option value="{{ $v }}" {{ request('ville') === $v ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small text-muted mb-1">Promotion</label>
                <select name="promotion" class="form-select form-select-sm">
                    <option value="">Toutes</option>
                    @foreach ($promotions as $p)
                        <option value="{{ $p }}" {{ request('promotion') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-faaci-primary btn-sm flex-grow-1">
                    <i class="bi bi-search me-1"></i> Filtrer
                </button>
                @if (request()->hasAny(['q','secteur','ville','promotion']))
                    <a href="{{ route('membre.annuaire') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

@if ($membres->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
        Aucun membre ne correspond à votre recherche.
    </div>
@else
    <div class="row g-3">
        @foreach ($membres as $m)
            <div class="col-sm-6 col-md-4 col-xl-3">
                <a href="{{ route('membre.annuaire.show', $m) }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                    <div class="card-body text-center py-4">
                        @if ($m->photo_url)
                            <img src="{{ $m->photo_url }}" alt="{{ $m->nom_complet }}"
                                 class="rounded-circle mb-3"
                                 style="width:72px;height:72px;object-fit:cover;">
                        @else
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width:72px;height:72px;background:var(--faaci-navy);color:#fff;font-size:1.5rem;font-weight:700;">
                                {{ mb_strtoupper(mb_substr($m->prenom,0,1)) }}{{ mb_strtoupper(mb_substr($m->nom,0,1)) }}
                            </div>
                        @endif
                        <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $m->nom_complet }}</h6>
                        @if ($m->secteur)
                            <p class="text-muted small mb-1 text-truncate">{{ $m->secteur }}</p>
                        @endif
                        @if ($m->ville)
                            <p class="text-muted small mb-0">
                                <i class="bi bi-geo-alt me-1"></i>{{ $m->ville }}
                            </p>
                        @endif
                        @if ($m->promotion_aiesec)
                            <span class="badge mt-2 rounded-pill"
                                  style="background:rgba(74,127,165,0.12);color:var(--faaci-steel);font-size:0.75rem;">
                                Promo {{ $m->promotion_aiesec }}
                            </span>
                        @endif
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $membres->links() }}
    </div>
@endif
@endsection
