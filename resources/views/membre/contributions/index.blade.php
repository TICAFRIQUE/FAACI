@extends('layouts.app')
@section('title', 'Mes investissements')
@section('breadcrumb')
    <li class="breadcrumb-item active">Mes investissements</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Mes investissements</h4>
    <a href="{{ route('membre.projets.index') }}" class="btn btn-faaci-primary btn-sm">
        <i class="bi bi-search me-1"></i> Voir les projets
    </a>
</div>

@if ($contributions->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-cash-stack fs-1 d-block mb-2 opacity-25"></i>
        Vous n'avez encore investi sur aucun projet.
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
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contributions as $c)
                        <tr>
                            <td data-label="Projet">
                                <a href="{{ route('membre.projets.show', $c->projet) }}"
                                   class="fw-medium text-faaci-steel text-decoration-none">
                                    {{ $c->projet->titre }}
                                </a>
                                <div class="text-muted" style="font-size:.75rem;">
                                    Promesse du {{ $c->created_at->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="small fw-medium" data-label="Promis">
                                {{ number_format($c->montant_promis, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="small" data-label="Payé">
                                @if ($c->montant_paye > 0)
                                    {{ number_format($c->montant_paye, 0, ',', ' ') }} FCFA
                                    @if ($c->statut === \App\Models\Contribution::STATUT_PARTIEL)
                                        @php $pct = min(100, round($c->montant_paye / $c->montant_promis * 100)); @endphp
                                        <div class="progress mt-1" style="height:4px;width:80px;">
                                            <div class="progress-bar bg-primary" style="width:{{ $pct }}%"></div>
                                        </div>
                                        <span class="text-muted" style="font-size:.7rem;">{{ $pct }}%</span>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td data-label="Statut">
                                @php
                                    $col = \App\Models\Contribution::statutsBadge()[$c->statut] ?? 'secondary';
                                    $lib = \App\Models\Contribution::statutsLibelles()[$c->statut] ?? $c->statut;
                                @endphp
                                <span class="badge bg-{{ $col }}">{{ $lib }}</span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="{{ route('membre.projets.show', $c->projet) }}"
                                       class="btn btn-sm btn-outline-secondary" title="Voir le projet">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if ($c->statut === \App\Models\Contribution::STATUT_PROMESSE)
                                        <form method="POST" action="{{ route('membre.contributions.annuler', $c) }}"
                                              class="m-0" data-confirm="Annuler cette promesse ?">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Annuler la promesse">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <p class="text-muted small mt-3 text-center">
        <i class="bi bi-info-circle me-1"></i>
        L'administration enregistre les paiements. Contactez-nous pour toute question.
    </p>
@endif
@endsection
