@extends('layouts.admin')

@section('title', 'Événements')
@section('page-title', 'Événements')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <p class="text-muted mb-0 small">Gérez les événements membres et publics.</p>
    <a href="{{ route('admin.evenements.create') }}" class="btn btn-faaci-navy">
        <i class="bi bi-plus-lg me-1"></i> Nouvel événement
    </a>
</div>

{{-- KPIs --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm border-start border-secondary border-3 h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Total</div>
                <div class="h3 fw-bold mb-0">{{ $compteurs['tous'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm border-start border-success border-3 h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Publiés</div>
                <div class="h3 fw-bold text-success mb-0">{{ $compteurs['publie'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm border-start border-warning border-3 h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Brouillons</div>
                <div class="h3 fw-bold text-warning mb-0">{{ $compteurs['brouillon'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Filtres rapides --}}
<form method="GET" class="d-flex flex-wrap gap-2 mb-3">
    <select name="statut" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
        <option value="tous" {{ request('statut','tous')==='tous'?'selected':'' }}>Tous les statuts</option>
        <option value="publie" {{ request('statut')==='publie'?'selected':'' }}>Publiés</option>
        <option value="brouillon" {{ request('statut')==='brouillon'?'selected':'' }}>Brouillons</option>
    </select>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher…"
           class="form-control form-control-sm" style="max-width:220px;">
    <button type="submit" class="btn btn-sm btn-outline-secondary">Filtrer</button>
    @if(request()->hasAny(['statut','q']))
        <a href="{{ route('admin.evenements.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-x-lg me-1"></i>Réinitialiser
        </a>
    @endif
</form>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0 p-md-3">
        <table class="table align-middle mb-0 table-mobile-cards">
            <thead class="table-light">
                <tr>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Inscrits</th>
                    <th>Public</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($evenements as $ev)
                    <tr>
                        <td data-label="Titre">
                            <a href="{{ route('admin.evenements.show', $ev) }}" class="fw-semibold text-decoration-none text-dark">
                                {{ $ev->titre }}
                            </a>
                            @if ($ev->lieu)
                                <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $ev->lieu }}</div>
                            @endif
                        </td>
                        <td data-label="Type"><span class="badge bg-light text-dark border">{{ $ev->type_libelle }}</span></td>
                        <td data-label="Date" class="small text-muted">
                            {{ $ev->date_debut->translatedFormat('d M Y') }}<br>
                            <span class="fw-medium">{{ $ev->date_debut->format('H:i') }}</span>
                        </td>
                        <td data-label="Inscrits" class="text-center">
                            {{ $ev->inscriptions_count }}
                            @if ($ev->capacite_max)
                                <span class="text-muted small">/ {{ $ev->capacite_max }}</span>
                            @endif
                        </td>
                        <td data-label="Public">
                            @if ($ev->est_public)
                                <span class="badge bg-info"><i class="bi bi-globe me-1"></i>Public</span>
                            @else
                                <span class="badge bg-secondary">Membres</span>
                            @endif
                        </td>
                        <td data-label="Statut">
                            <form method="POST" action="{{ route('admin.evenements.basculer', $ev) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="badge border-0 {{ $ev->statut === 'publie' ? 'bg-success' : 'bg-warning text-dark' }}"
                                        title="Cliquer pour changer">
                                    {{ $ev->statut === 'publie' ? 'Publié' : 'Brouillon' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.evenements.show', $ev) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.evenements.edit', $ev) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.evenements.destroy', $ev) }}" class="d-inline"
                                  data-confirm="Supprimer « {{ $ev->titre }} » ?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Aucun événement.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($evenements->hasPages())
        <div class="card-footer bg-white">{{ $evenements->links() }}</div>
    @endif
</div>
@endsection
