@extends('layouts.app')

@section('title', 'Mes candidatures')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.competitions.index') }}">Compétitions</a></li>
    <li class="breadcrumb-item active">Mes candidatures</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Mes candidatures</h4>
    <a href="{{ route('membre.competitions.index') }}" class="btn btn-faaci-primary btn-sm">
        <i class="bi bi-trophy me-1"></i> Voir les compétitions
    </a>
</div>

@if ($candidatures->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
        <p>Vous n'avez encore soumis aucune candidature.</p>
        <a href="{{ route('membre.competitions.index') }}" class="btn btn-faaci-primary btn-sm">
            Voir les compétitions ouvertes
        </a>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Compétition</th>
                            <th>Mon projet</th>
                            <th>Statut</th>
                            <th>Note du jury</th>
                            <th>Soumise le</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($candidatures as $cand)
                            @php
                                $sb = \App\Models\CandidatureCompetition::statutsBadge()[$cand->statut] ?? 'secondary';
                                $sl = \App\Models\CandidatureCompetition::statuts()[$cand->statut] ?? $cand->statut;
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $cand->competition?->titre }}</div>
                                    @if ($cand->competition?->date_pitch)
                                        <div class="text-muted" style="font-size:0.75rem;">
                                            Pitch : {{ $cand->competition->date_pitch->translatedFormat('d M Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $cand->titre_projet }}</div>
                                    <div class="text-muted" style="font-size:0.75rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        {{ $cand->resume_projet }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $sb }}">{{ $sl }}</span>
                                    @if ($cand->statut === 'gagnante')
                                        <span class="ms-1">🏆</span>
                                    @endif
                                </td>
                                <td class="text-muted fst-italic">
                                    {{ $cand->note_jury ? Str::limit($cand->note_jury, 80) : '—' }}
                                </td>
                                <td class="text-muted">{{ $cand->created_at->translatedFormat('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('membre.competitions.show', $cand->competition) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $candidatures->links() }}
    </div>
@endif
@endsection
