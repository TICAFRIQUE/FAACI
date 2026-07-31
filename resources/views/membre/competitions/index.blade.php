@extends('layouts.app')

@section('title', 'Compétitions Alumni')

@section('breadcrumb')
    <li class="breadcrumb-item active">Compétitions</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Compétitions Alumni</h4>
    <a href="{{ route('membre.competitions.mes-candidatures') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-list-check me-1"></i> Mes candidatures
    </a>
</div>

@if ($competitions->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-trophy fs-1 d-block mb-2 opacity-25"></i>
        <p>Aucune compétition ouverte pour le moment.</p>
        <p class="small">Revenez bientôt, la fondation publie régulièrement des appels à projets.</p>
    </div>
@else
    <div class="row g-4">
        @foreach ($competitions as $competition)
            @php
                $déjàCandidat = in_array($competition->id, $mesCandidatures);
                $badge = \App\Models\Competition::statutsBadge()[$competition->statut] ?? 'secondary';
                $lib   = \App\Models\Competition::statuts()[$competition->statut] ?? $competition->statut;
            @endphp
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100 d-flex flex-column">
                    <div class="card-body flex-grow-1">
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <h6 class="fw-bold mb-0">{{ $competition->titre }}</h6>
                            <span class="badge bg-{{ $badge }} flex-shrink-0">{{ $lib }}</span>
                        </div>

                        @if ($competition->description)
                            <p class="text-muted small mb-3" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $competition->description }}
                            </p>
                        @endif

                        <div class="d-flex flex-wrap gap-3 small text-muted">
                            @if ($competition->budget)
                                <span>
                                    <i class="bi bi-cash-coin me-1 text-success"></i>
                                    {{ number_format($competition->budget, 0, ',', ' ') }} FCFA
                                </span>
                            @endif
                            @if ($competition->date_limite_candidature)
                                <span>
                                    <i class="bi bi-calendar-event me-1"></i>
                                    Limite : {{ $competition->date_limite_candidature->translatedFormat('d M Y') }}
                                </span>
                            @endif
                            @if ($competition->date_pitch)
                                <span>
                                    <i class="bi bi-mic me-1"></i>
                                    Pitch : {{ $competition->date_pitch->translatedFormat('d M Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pt-0">
                        @if ($déjàCandidat)
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Candidature soumise</span>
                                <a href="{{ route('membre.competitions.show', $competition) }}" class="btn btn-outline-secondary btn-sm ms-auto">
                                    Voir
                                </a>
                            </div>
                        @else
                            <a href="{{ route('membre.competitions.show', $competition) }}" class="btn btn-faaci-primary btn-sm w-100">
                                <i class="bi bi-arrow-right me-1"></i> Postuler
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $competitions->links() }}
    </div>
@endif
@endsection
