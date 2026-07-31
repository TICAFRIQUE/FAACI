@extends('layouts.app')

@section('title', 'Mes dons')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mes dons</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Mes dons à la fondation</h4>
    <a href="{{ route('membre.dons.create') }}" class="btn btn-faaci-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Nouveau don
    </a>
</div>

@if ($dons->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-gift fs-1 d-block mb-2 opacity-25"></i>
        Vous n'avez encore effectué aucun don à la fondation.
        <div class="mt-3">
            <a href="{{ route('membre.dons.create') }}" class="btn btn-faaci-primary btn-sm">
                Faire un don
            </a>
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-mobile-cards">
                <thead class="table-light">
                    <tr>
                        <th>Nature</th>
                        <th>Libellé</th>
                        <th>Montant / Valeur</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dons as $don)
                        @php
                            $badges = [
                                'en_attente' => 'warning',
                                'confirme'   => 'success',
                                'rejete'     => 'danger',
                                'annule'     => 'secondary',
                            ];
                            $natures = \App\Models\Don::natures();
                        @endphp
                        <tr>
                            <td data-label="Nature">
                                <span class="badge bg-light text-dark border">
                                    {{ $natures[$don->nature] ?? $don->nature }}
                                </span>
                            </td>
                            <td data-label="Libellé" class="fw-medium">{{ $don->libelle }}</td>
                            <td data-label="Montant" class="small fw-medium">
                                @if ($don->nature === 'argent' && $don->montant)
                                    {{ number_format($don->montant, 0, ',', ' ') }} FCFA
                                @elseif ($don->valeur_estimee)
                                    {{ $don->valeur_estimee }}
                                @else
                                    —
                                @endif
                            </td>
                            <td data-label="Statut">
                                <span class="badge bg-{{ $badges[$don->statut] ?? 'secondary' }}">
                                    {{ \App\Models\Don::statutsLibelles()[$don->statut] }}
                                </span>
                            </td>
                            <td data-label="Date" class="small text-muted">{{ $don->created_at->diffForHumans() }}</td>
                            <td>
                                @if ($don->statut === 'en_attente')
                                    <form method="POST" action="{{ route('membre.dons.annuler', $don) }}"
                                          class="d-inline" data-confirm="Annuler ce don ?">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif
                                @if ($don->statut === 'rejete' && $don->motif_rejet)
                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                            data-bs-toggle="tooltip" title="{{ $don->motif_rejet }}">
                                        <i class="bi bi-info-circle"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
