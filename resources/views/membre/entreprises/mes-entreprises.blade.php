@extends('layouts.app')

@section('title', 'Mes entreprises')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.entreprises.index') }}">Entreprises Alumni</a></li>
    <li class="breadcrumb-item active">Mes entreprises</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Mes entreprises</h4>
    <a href="{{ route('membre.entreprises.create') }}" class="btn btn-faaci-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Ajouter une entreprise
    </a>
</div>

@if ($entreprises->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-building fs-1 d-block mb-2 opacity-25"></i>
        Vous n'avez pas encore référencé d'entreprise.
        <div class="mt-3">
            <a href="{{ route('membre.entreprises.create') }}" class="btn btn-faaci-primary btn-sm">
                Référencer mon entreprise
            </a>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach ($entreprises as $e)
            @php
                $badges = [
                    'en_attente' => ['warning', 'En attente de validation'],
                    'actif'      => ['success', 'Active'],
                    'rejete'     => ['danger', 'Rejetée'],
                    'inactif'    => ['secondary', 'Inactive'],
                ];
                [$bCol, $bLib] = $badges[$e->statut] ?? ['secondary', $e->statut];
            @endphp
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex gap-3">
                        @if ($e->logo_url)
                            <img src="{{ $e->logo_url }}" alt="{{ $e->nom }}" class="rounded"
                                 style="width:56px;height:56px;object-fit:cover;flex-shrink:0;">
                        @else
                            <div class="rounded d-flex align-items-center justify-content-center"
                                 style="width:56px;height:56px;background:var(--faaci-navy);color:#fff;font-size:1.4rem;flex-shrink:0;">
                                <i class="bi bi-building"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1 min-width-0">
                            <div class="d-flex align-items-start justify-content-between gap-2">
                                <h6 class="fw-bold mb-1">{{ $e->nom }}</h6>
                                <span class="badge bg-{{ $bCol }} flex-shrink-0">{{ $bLib }}</span>
                            </div>
                            @if ($e->secteur)
                                <p class="text-muted small mb-1">{{ $e->secteur }}</p>
                            @endif
                            @if ($e->localisation)
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $e->localisation }}
                                </p>
                            @endif
                            @if ($e->statut === 'rejete' && $e->motif_rejet)
                                <p class="text-danger small mt-1 mb-0">
                                    <i class="bi bi-info-circle me-1"></i>{{ $e->motif_rejet }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 d-flex gap-2 justify-content-end">
                        @if ($e->statut === 'actif')
                            <a href="{{ route('membre.entreprises.show', $e) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye me-1"></i> Voir
                            </a>
                        @endif
                        <a href="{{ route('membre.entreprises.edit', $e) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i> Modifier
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
