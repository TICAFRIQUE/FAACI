@extends('layouts.app')

@section('title', 'Mes offres d\'emploi')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mes offres</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Mes offres d'emploi</h4>
    <a href="{{ route('membre.emplois.create') }}" class="btn btn-faaci-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Nouvelle offre
    </a>
</div>

@if ($offres->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-briefcase fs-1 d-block mb-2 opacity-25"></i>
        Vous n'avez pas encore publié d'offre.
        <div class="mt-3">
            <a href="{{ route('membre.emplois.create') }}" class="btn btn-faaci-primary btn-sm">Publier une offre</a>
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-mobile-cards">
                <thead class="table-light">
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Candidatures</th>
                        <th>Statut</th>
                        <th>Publié</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($offres as $offre)
                        @php
                            $colors = ['cdi'=>'success','cdd'=>'primary','stage'=>'info','freelance'=>'warning','alternance'=>'secondary'];
                            $sc = ['en_attente'=>'warning','active'=>'success','brouillon'=>'secondary','expiree'=>'dark','rejetee'=>'danger'];
                        @endphp
                        <tr>
                            <td data-label="Titre" class="fw-medium">{{ $offre->titre }}</td>
                            <td data-label="Type">
                                <span class="badge bg-{{ $colors[$offre->type_contrat] ?? 'secondary' }}">{{ $offre->type_libelle }}</span>
                            </td>
                            <td data-label="Candidatures" class="text-center">{{ $offre->candidatures_count }}</td>
                            <td data-label="Statut">
                                <span class="badge bg-{{ $sc[$offre->statut] ?? 'secondary' }}">{{ $offre->statut_libelle }}</span>
                            </td>
                            <td data-label="Publié" class="small text-muted">{{ $offre->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('membre.emplois.show', $offre) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if (in_array($offre->statut, ['en_attente', 'rejetee']))
                                    <a href="{{ route('membre.emplois.edit', $offre) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('membre.emplois.destroy', $offre) }}" class="d-inline"
                                          onsubmit="return confirm('Supprimer cette offre ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
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
