@extends('layouts.admin')

@section('title', $competition->titre)

@section('content')
<div class="mb-3 d-flex gap-2 align-items-center">
    <a href="{{ route('admin.competitions.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
    <a href="{{ route('admin.competitions.edit', $competition) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-pencil me-1"></i> Modifier
    </a>
    <a href="{{ route('admin.competitions.candidatures', $competition) }}" class="btn btn-faaci-primary btn-sm">
        <i class="bi bi-people me-1"></i> Gérer les candidatures
        <span class="badge bg-light text-dark ms-1">{{ $competition->candidatures->count() }}</span>
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                <i class="bi bi-trophy me-1 text-faaci-steel"></i> {{ $competition->titre }}
            </div>
            <div class="card-body">
                @php
                    $badge = \App\Models\Competition::statutsBadge()[$competition->statut] ?? 'secondary';
                    $lib   = \App\Models\Competition::statuts()[$competition->statut] ?? $competition->statut;
                @endphp
                <span class="badge bg-{{ $badge }} mb-3">{{ $lib }}</span>

                @if ($competition->description)
                    <div class="text-muted" style="white-space:pre-line;">{{ $competition->description }}</div>
                    <hr>
                @endif

                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted fw-normal small">Budget alloué</dt>
                    <dd class="col-sm-8 fw-medium">
                        {{ $competition->budget ? number_format($competition->budget, 0, ',', ' ').' FCFA' : '—' }}
                    </dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Date limite</dt>
                    <dd class="col-sm-8">
                        {{ $competition->date_limite_candidature?->translatedFormat('d F Y') ?? '—' }}
                    </dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Date du pitch</dt>
                    <dd class="col-sm-8">
                        {{ $competition->date_pitch?->translatedFormat('d F Y') ?? '—' }}
                    </dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Créée par</dt>
                    <dd class="col-sm-8">{{ $competition->createur?->nom_complet ?? '—' }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Créée le</dt>
                    <dd class="col-sm-8">{{ $competition->created_at->translatedFormat('d F Y') }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Résumé candidatures --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                <i class="bi bi-people me-1 text-faaci-steel"></i> Candidatures
            </div>
            <div class="card-body">
                @php $total = $competition->candidatures->count(); @endphp
                @if ($total === 0)
                    <p class="text-muted small mb-0">Aucune candidature reçue.</p>
                @else
                    @foreach (\App\Models\CandidatureCompetition::statuts() as $s => $l)
                        @php $nb = $competition->candidatures->where('statut', $s)->count(); @endphp
                        @if ($nb > 0)
                            @php $b = \App\Models\CandidatureCompetition::statutsBadge()[$s] ?? 'secondary'; @endphp
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-muted">{{ $l }}</span>
                                <span class="badge bg-{{ $b }}">{{ $nb }}</span>
                            </div>
                        @endif
                    @endforeach
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold">Total</span>
                        <span class="badge bg-dark">{{ $total }}</span>
                    </div>
                @endif
            </div>
            @if ($total > 0)
                <div class="card-footer bg-transparent">
                    <a href="{{ route('admin.competitions.candidatures', $competition) }}" class="btn btn-faaci-primary btn-sm w-100">
                        <i class="bi bi-list-ul me-1"></i> Voir toutes les candidatures
                    </a>
                </div>
            @endif
        </div>

        {{-- Gagnants --}}
        @php $gagnants = $competition->candidatures->where('statut', 'gagnante'); @endphp
        @if ($gagnants->count() > 0)
            <div class="card border-0 shadow-sm" style="border-top:3px solid #f59e0b !important;">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-trophy-fill text-warning me-1"></i> Vainqueur(s)
                </div>
                <div class="card-body pt-0">
                    @foreach ($gagnants as $g)
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-star-fill text-warning"></i>
                            <div>
                                <div class="fw-semibold small">{{ $g->candidat?->nom_complet }}</div>
                                <div class="text-muted" style="font-size:0.78rem;">{{ $g->titre_projet }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
