@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('breadcrumb')
    <li class="breadcrumb-item active">Tableau de bord</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Bonjour, {{ $membre->prenom }} 👋</h4>
        <p class="text-muted small mb-0">Bienvenue dans votre espace membre FAACI</p>
    </div>
    <a href="{{ route('membre.profil.edit') }}" class="btn btn-faaci-primary btn-sm">
        <i class="bi bi-pencil me-1"></i> Mon profil
    </a>
</div>

@if ($membre->completeness < 70)
    <div class="alert alert-warning d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
        <div>
            Votre profil est complété à <strong>{{ $membre->completeness }} %</strong>.
            <a href="{{ route('membre.profil.edit') }}" class="alert-link ms-1">Compléter maintenant →</a>
        </div>
    </div>
@endif

{{-- KPIs --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100 text-center">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-faaci-navy">{{ $stats['membres_actifs'] }}</div>
                <div class="text-muted small">Membres actifs</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100 text-center">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-faaci-steel">{{ $stats['projets_actifs'] }}</div>
                <div class="text-muted small">Projets en financement</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100 text-center">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-success">{{ $stats['mes_projets'] }}</div>
                <div class="text-muted small">Mes projets</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100 text-center">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold" style="color:var(--faaci-steel)">{{ $stats['mes_contributions'] }}</div>
                <div class="text-muted small">Mes contributions</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Prochains événements --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold pt-3 pb-2 border-bottom d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-event me-1 text-faaci-steel"></i> Prochains événements</span>
                <a href="{{ route('membre.evenements.index') }}" class="btn btn-sm btn-outline-secondary btn-sm">Tous voir</a>
            </div>
            <div class="card-body p-0">
                @forelse ($prochains_evenements as $ev)
                    <a href="{{ route('membre.evenements.show', $ev) }}"
                       class="d-flex align-items-center gap-3 px-3 py-3 text-decoration-none text-dark border-bottom">
                        <div class="text-center flex-shrink-0"
                             style="width:44px;background:rgba(13,31,60,0.06);border-radius:8px;padding:4px 0;">
                            <div class="fw-bold" style="font-size:1.1rem;line-height:1;color:var(--faaci-navy);">
                                {{ $ev->date_debut->format('d') }}
                            </div>
                            <div style="font-size:0.65rem;text-transform:uppercase;color:var(--faaci-steel);">
                                {{ $ev->date_debut->translatedFormat('M') }}
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-medium text-truncate">{{ $ev->titre }}</div>
                            <div class="text-muted small">
                                <i class="bi bi-clock me-1"></i>{{ $ev->date_debut->format('H:i') }}
                                @if ($ev->lieu) · <i class="bi bi-geo-alt me-1"></i>{{ $ev->lieu }} @endif
                            </div>
                        </div>
                        <span class="badge bg-light text-dark border small flex-shrink-0">{{ $ev->type_libelle }}</span>
                    </a>
                @empty
                    <div class="text-muted small text-center py-4">
                        <i class="bi bi-calendar-x d-block fs-3 mb-1 opacity-25"></i>
                        Aucun événement à venir.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Dernières offres d'emploi --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold pt-3 pb-2 border-bottom d-flex justify-content-between align-items-center">
                <span><i class="bi bi-briefcase me-1 text-faaci-steel"></i> Offres d'emploi</span>
                <a href="{{ route('membre.emplois.index') }}" class="btn btn-sm btn-outline-secondary">Toutes voir</a>
            </div>
            <div class="card-body p-0">
                @php $colors = ['cdi'=>'success','cdd'=>'primary','stage'=>'info','freelance'=>'warning','alternance'=>'secondary']; @endphp
                @forelse ($dernieres_offres as $offre)
                    <a href="{{ route('membre.emplois.show', $offre) }}"
                       class="d-flex align-items-center gap-3 px-3 py-3 text-decoration-none text-dark border-bottom">
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-medium text-truncate">{{ $offre->titre }}</div>
                            <div class="text-muted small">
                                @if ($offre->localisation)<i class="bi bi-geo-alt me-1"></i>{{ $offre->localisation }} · @endif
                                <i class="bi bi-person me-1"></i>{{ $offre->auteur->nom_complet }}
                            </div>
                        </div>
                        <span class="badge bg-{{ $colors[$offre->type_contrat] ?? 'secondary' }} flex-shrink-0">{{ $offre->type_libelle }}</span>
                    </a>
                @empty
                    <div class="text-muted small text-center py-4">
                        <i class="bi bi-briefcase d-block fs-3 mb-1 opacity-25"></i>
                        Aucune offre disponible.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Raccourcis --}}
<div class="row g-3 mt-2">
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('membre.annuaire') }}" class="card border-0 shadow-sm text-decoration-none h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:42px;height:42px;background:rgba(74,127,165,0.12);">
                    <i class="bi bi-people text-faaci-steel fs-5"></i>
                </div>
                <div>
                    <div class="fw-semibold text-dark small">Annuaire</div>
                    <div class="text-muted" style="font-size:0.75rem;">Trouver un membre</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('membre.projets.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:42px;height:42px;background:rgba(74,127,165,0.12);">
                    <i class="bi bi-lightbulb text-faaci-steel fs-5"></i>
                </div>
                <div>
                    <div class="fw-semibold text-dark small">Projets</div>
                    <div class="text-muted" style="font-size:0.75rem;">Financer un projet</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('membre.evenements.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:42px;height:42px;background:rgba(74,127,165,0.12);">
                    <i class="bi bi-calendar-event text-faaci-steel fs-5"></i>
                </div>
                <div>
                    <div class="fw-semibold text-dark small">Événements</div>
                    <div class="text-muted" style="font-size:0.75rem;">Voir le calendrier</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('membre.emplois.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:42px;height:42px;background:rgba(74,127,165,0.12);">
                    <i class="bi bi-briefcase text-faaci-steel fs-5"></i>
                </div>
                <div>
                    <div class="fw-semibold text-dark small">Emplois</div>
                    <div class="text-muted" style="font-size:0.75rem;">Offres disponibles</div>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
