@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')

<p class="text-muted mb-4">
    Bienvenue, <strong>{{ auth()->user()->prenom }}</strong>. Voici l'état de la plateforme FAACI.
</p>

{{-- ── KPIs principaux ── --}}
<div class="row g-3 mb-4">
    {{-- Membres --}}
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:48px;height:48px;background:rgba(13,31,60,.08);">
                        <i class="bi bi-people-fill fs-4 text-faaci-navy"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="text-muted small">Membres actifs</div>
                        <div class="h3 fw-bold text-faaci-navy mb-0">{{ number_format($membres->actifs) }}</div>
                    </div>
                </div>
                @if ($membres->en_attente > 0)
                    <div class="mt-2">
                        <a href="{{ route('admin.membres.index', ['statut' => 'en_attente']) }}"
                           class="badge bg-warning text-dark text-decoration-none">
                            {{ $membres->en_attente }} en attente de validation
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Projets --}}
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:48px;height:48px;background:rgba(74,127,165,.1);">
                        <i class="bi bi-lightbulb-fill fs-4 text-faaci-steel"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="text-muted small">Projets actifs</div>
                        <div class="h3 fw-bold text-faaci-navy mb-0">
                            {{ $projets->en_financement + $projets->en_cours }}
                        </div>
                    </div>
                </div>
                @if ($projets->en_attente > 0)
                    <div class="mt-2">
                        <a href="{{ route('admin.projets.index') }}"
                           class="badge bg-warning text-dark text-decoration-none">
                            {{ $projets->en_attente }} à valider
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Contributions à valider --}}
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:48px;height:48px;background:rgba(25,135,84,.1);">
                        <i class="bi bi-cash-stack fs-4 text-success"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="text-muted small">Paiements à valider</div>
                        <div class="h3 fw-bold text-faaci-navy mb-0">{{ $contributions->a_valider }}</div>
                    </div>
                </div>
                @if ($contributions->a_valider > 0)
                    <div class="mt-2">
                        <a href="{{ route('admin.contributions.index') }}"
                           class="badge bg-info text-decoration-none">
                            Voir les déclarations
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Fonds collectés --}}
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:48px;height:48px;background:rgba(74,127,165,.1);">
                        <i class="bi bi-graph-up-arrow fs-4 text-faaci-steel"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="text-muted small">Fonds collectés (validés)</div>
                        <div class="fw-bold text-faaci-navy mb-0" style="font-size:1.1rem;">
                            {{ number_format($contributions->fonds_collectes, 0, ',', ' ') }} FCFA
                        </div>
                    </div>
                </div>
                <div class="mt-2 small text-muted">
                    {{ $contributions->validees }} contribution(s) validée(s)
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Alertes urgentes ── --}}
@if ($membres->en_attente > 0 || $projets->en_attente > 0 || $contributions->a_valider > 0)
    <div class="card border-0 shadow-sm mb-4 border-start border-warning border-4">
        <div class="card-body py-3">
            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Actions requises</h6>
            <div class="d-flex flex-wrap gap-2">
                @if ($membres->en_attente > 0)
                    <a href="{{ route('admin.membres.index', ['statut' => 'en_attente']) }}"
                       class="btn btn-sm btn-warning">
                        <i class="bi bi-person-check me-1"></i>
                        {{ $membres->en_attente }} demande(s) d'adhésion
                    </a>
                @endif
                @if ($projets->en_attente > 0)
                    <a href="{{ route('admin.projets.index') }}" class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-lightbulb me-1"></i>
                        {{ $projets->en_attente }} projet(s) à valider
                    </a>
                @endif
                @if ($contributions->a_valider > 0)
                    <a href="{{ route('admin.contributions.index') }}" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-cash-coin me-1"></i>
                        {{ $contributions->a_valider }} paiement(s) à valider
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif

{{-- ── Deux colonnes : projets en attente + contributions à valider ── --}}
<div class="row g-4 mb-4">
    {{-- Projets en attente --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex align-items-center justify-content-between pt-3 pb-2 border-bottom">
                <span class="fw-semibold">
                    <i class="bi bi-lightbulb me-1 text-warning"></i> Projets en attente
                </span>
                <a href="{{ route('admin.projets.index') }}" class="btn btn-sm btn-outline-secondary">
                    Voir tout
                </a>
            </div>
            <div class="card-body p-0">
                @forelse ($projetsEnAttente as $p)
                    <div class="d-flex align-items-start gap-3 px-3 py-3 border-bottom">
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-medium text-truncate small">{{ $p->titre }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">
                                {{ $p->porteur->nom_complet }} ·
                                {{ $p->created_at->diffForHumans() }}
                                @if ($p->montant_cible)
                                    · {{ number_format($p->montant_cible, 0, ',', ' ') }} FCFA
                                @endif
                            </div>
                        </div>
                        <div class="d-flex gap-1 flex-shrink-0">
                            <form method="POST" action="{{ route('admin.projets.valider', $p) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success"
                                        title="Valider → En financement"
                                        onclick="return confirm('Mettre ce projet en financement ?')">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            <a href="{{ route('admin.projets.show', $p) }}"
                               class="btn btn-sm btn-outline-secondary" title="Détail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-check-circle text-success d-block fs-4 mb-1"></i>
                        Aucun projet en attente.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Contributions à valider --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex align-items-center justify-content-between pt-3 pb-2 border-bottom">
                <span class="fw-semibold">
                    <i class="bi bi-cash-coin me-1 text-info"></i> Paiements déclarés à valider
                </span>
                <a href="{{ route('admin.contributions.index') }}" class="btn btn-sm btn-outline-secondary">
                    Voir tout
                </a>
            </div>
            <div class="card-body p-0">
                @forelse ($contribsAValider as $c)
                    @php
                        $statBadge = $c->statut === 'partial' ? 'primary' : 'info';
                        $statLib   = $c->statut === 'partial' ? 'Partiel' : 'Déclaré';
                    @endphp
                    <div class="d-flex align-items-start gap-3 px-3 py-3 border-bottom">
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-medium small text-truncate">{{ $c->contributeur->nom_complet }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">
                                {{ $c->projet->titre }} ·
                                <strong>{{ number_format($c->montant_paye, 0, ',', ' ') }} FCFA</strong>
                                / {{ number_format($c->montant_promis, 0, ',', ' ') }} FCFA promis
                            </div>
                            <span class="badge bg-{{ $statBadge }} mt-1" style="font-size:0.65rem;">{{ $statLib }}</span>
                        </div>
                        <div class="d-flex gap-1 flex-shrink-0">
                            <form method="POST" action="{{ route('admin.contributions.valider', $c) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success"
                                        title="Valider le paiement"
                                        onclick="return confirm('Valider ce paiement de {{ number_format($c->montant_paye, 0, ',', ' ') }} FCFA ?')">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            <a href="{{ route('admin.contributions.show', $c) }}"
                               class="btn btn-sm btn-outline-secondary" title="Détail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-check-circle text-success d-block fs-4 mb-1"></i>
                        Aucun paiement en attente de validation.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── Raccourcis CMS ── --}}
<h6 class="text-muted fw-semibold text-uppercase mb-3" style="font-size:0.72rem;letter-spacing:.08em;">
    Gestion du site vitrine
</h6>
<div class="row g-3">
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('admin.slides.index') }}" class="card border-0 shadow-sm h-100 text-decoration-none">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="bi bi-images fs-4 text-faaci-steel"></i>
                <div>
                    <div class="fw-semibold text-faaci-navy small">Slider</div>
                    <div class="text-muted" style="font-size:0.75rem;">{{ $cms['slides_actives'] }}/{{ $cms['slides'] }} slides actives</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('admin.valeurs.index') }}" class="card border-0 shadow-sm h-100 text-decoration-none">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="bi bi-gem fs-4 text-faaci-steel"></i>
                <div>
                    <div class="fw-semibold text-faaci-navy small">Valeurs</div>
                    <div class="text-muted" style="font-size:0.75rem;">{{ $cms['valeurs'] }} valeurs</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('admin.equipe.index') }}" class="card border-0 shadow-sm h-100 text-decoration-none">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="bi bi-people fs-4 text-faaci-steel"></i>
                <div>
                    <div class="fw-semibold text-faaci-navy small">Équipe</div>
                    <div class="text-muted" style="font-size:0.75rem;">{{ $cms['equipe'] }} membres</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('admin.parametres.edit') }}" class="card border-0 shadow-sm h-100 text-decoration-none">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <i class="bi bi-gear fs-4 text-faaci-steel"></i>
                <div>
                    <div class="fw-semibold text-faaci-navy small">Paramètres</div>
                    <div class="text-muted" style="font-size:0.75rem;">Contact, réseaux, footer</div>
                </div>
            </div>
        </a>
    </div>
</div>

@endsection
