@extends('layouts.app')

@section('title', 'Mes projets')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mes projets</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Mes projets</h4>
    <a href="{{ route('membre.projets.create') }}" class="btn btn-faaci-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Nouveau projet
    </a>
</div>

@if ($projets->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-lightbulb fs-1 d-block mb-2 opacity-25"></i>
        Vous n'avez pas encore de projet.
        <div class="mt-3">
            <a href="{{ route('membre.projets.create') }}" class="btn btn-faaci-primary btn-sm">
                Soumettre mon premier projet
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
                        <th>Type</th>
                        <th>Montant cible</th>
                        <th>Collecté</th>
                        <th>Statut</th>
                        <th>Contribs</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projets as $projet)
                        @php
                            $badges = [
                                'brouillon'      => 'secondary',
                                'en_attente'     => 'warning',
                                'valide'         => 'info',
                                'en_financement' => 'primary',
                                'finance'        => 'success',
                                'en_cours'       => 'success',
                                'termine'        => 'dark',
                                'rejete'         => 'danger',
                            ];
                        @endphp
                        <tr>
                            <td data-label="Projet">
                                <a href="{{ route('membre.projets.show', $projet) }}"
                                   class="fw-medium text-decoration-none text-dark">
                                    {{ $projet->titre }}
                                </a>
                                <div class="text-muted small">{{ $projet->created_at->diffForHumans() }}</div>
                            </td>
                            <td data-label="Type">
                                <span class="badge rounded-pill"
                                      style="background:rgba(74,127,165,0.12);color:var(--faaci-steel);font-size:0.72rem;">
                                    {{ $projet->type_financement === 'fixe' ? 'Fixe' : 'Ouvert' }}
                                </span>
                            </td>
                            <td class="small" data-label="Cible">
                                {{ $projet->montant_cible ? number_format($projet->montant_cible, 0, ',', ' ').' FCFA' : '—' }}
                            </td>
                            <td class="small fw-medium" data-label="Collecté">
                                {{ number_format($projet->montant_collecte, 0, ',', ' ') }} FCFA
                            </td>
                            <td data-label="Statut">
                                <span class="badge bg-{{ $badges[$projet->statut] ?? 'secondary' }}">
                                    {{ \App\Models\Projet::statutsLibelles()[$projet->statut] }}
                                </span>
                            </td>
                            <td class="text-center small" data-label="Contribs">{{ $projet->contributions_count }}</td>
                            <td>
                                <a href="{{ route('membre.projets.show', $projet) }}"
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
@endif
@endsection
