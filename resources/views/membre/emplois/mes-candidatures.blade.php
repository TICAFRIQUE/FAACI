@extends('layouts.app')

@section('title', 'Mes candidatures')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mes candidatures</li>
@endsection

@section('content')
<h4 class="fw-bold mb-4">Mes candidatures</h4>

@if ($candidatures->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-send fs-1 d-block mb-2 opacity-25"></i>
        Vous n'avez pas encore postulé à une offre.
        <div class="mt-3">
            <a href="{{ route('membre.emplois.index') }}" class="btn btn-faaci-primary btn-sm">Voir les offres</a>
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-mobile-cards">
                <thead class="table-light">
                    <tr>
                        <th>Offre</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Postulé</th>
                        <th>Note recruteur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($candidatures as $c)
                        @php
                            $colors = ['cdi'=>'success','cdd'=>'primary','stage'=>'info','freelance'=>'warning','alternance'=>'secondary'];
                            $sc = ['soumise'=>'warning','en_cours'=>'info','acceptee'=>'success','rejetee'=>'danger'];
                        @endphp
                        <tr>
                            <td data-label="Offre">
                                <a href="{{ route('membre.emplois.show', $c->offre) }}"
                                   class="fw-medium text-decoration-none text-dark">
                                    {{ $c->offre->titre }}
                                </a>
                            </td>
                            <td data-label="Type">
                                <span class="badge bg-{{ $colors[$c->offre->type_contrat] ?? 'secondary' }}">{{ $c->offre->type_libelle }}</span>
                            </td>
                            <td data-label="Statut">
                                <span class="badge bg-{{ $sc[$c->statut] ?? 'secondary' }}">{{ $c->statut_libelle }}</span>
                            </td>
                            <td data-label="Postulé" class="small text-muted">{{ $c->created_at->diffForHumans() }}</td>
                            <td data-label="Note" class="small text-muted">{{ $c->note_recruteur ?: '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
