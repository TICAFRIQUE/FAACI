@extends('layouts.app')

@section('title', 'Mes contributions')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mes contributions</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Mes contributions</h4>
    <a href="{{ route('membre.projets.index') }}" class="btn btn-faaci-primary btn-sm">
        <i class="bi bi-search me-1"></i> Voir les projets
    </a>
</div>

@if ($contributions->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-cash-stack fs-1 d-block mb-2 opacity-25"></i>
        Vous n'avez encore soutenu aucun projet.
        <div class="mt-3">
            <a href="{{ route('membre.projets.index') }}" class="btn btn-faaci-primary btn-sm">
                Découvrir les projets en financement
            </a>
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-mobile-cards">
                <thead class="table-light">
                    <tr>
                        <th>Projet</th>
                        <th>Promis</th>
                        <th>Payé</th>
                        <th>Moyen</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contributions as $c)
                        @php
                            $badges = [
                                'pending'   => 'warning',
                                'confirmed' => 'info',
                                'paid'      => 'success',
                                'partial'   => 'primary',
                                'cancelled' => 'secondary',
                            ];
                        @endphp
                        <tr>
                            <td data-label="Projet">
                                <a href="{{ route('membre.projets.show', $c->projet) }}"
                                   class="fw-medium text-decoration-none text-dark">
                                    {{ $c->projet->titre }}
                                </a>
                            </td>
                            <td class="small fw-medium" data-label="Promis">{{ number_format($c->montant_promis, 0, ',', ' ') }} FCFA</td>
                            <td class="small" data-label="Payé">
                                {{ $c->montant_paye > 0 ? number_format($c->montant_paye, 0, ',', ' ').' FCFA' : '—' }}
                            </td>
                            <td class="small text-muted" data-label="Moyen">
                                {{ $c->moyen_paiement ? \App\Models\Contribution::moyensPaiement()[$c->moyen_paiement] : '—' }}
                            </td>
                            <td data-label="Statut">
                                <span class="badge bg-{{ $badges[$c->statut] ?? 'secondary' }}">
                                    {{ \App\Models\Contribution::statutsLibelles()[$c->statut] }}
                                </span>
                            </td>
                            <td class="small text-muted" data-label="Date">{{ $c->created_at->diffForHumans() }}</td>
                            <td>
                                @if (in_array($c->statut, ['pending', 'partial']))
                                    <a href="{{ route('membre.contributions.paiement', $c) }}"
                                       class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-cash me-1"></i> Déclarer paiement
                                    </a>
                                @endif
                                @if ($c->statut === 'pending')
                                    <form method="POST" action="{{ route('membre.contributions.annuler', $c) }}"
                                          class="d-inline" onsubmit="return confirm('Annuler cette promesse ?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
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
