@extends('layouts.admin')
@section('title', 'Types de cotisation')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Types de cotisation</h4>
    <a href="{{ route('admin.cotisations.types.create') }}" class="btn btn-faaci-navy btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Nouveau type
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Fréquence</th>
                    <th>Montant standard</th>
                    <th>Périodes</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($types as $type)
                    <tr>
                        <td class="fw-medium">{{ $type->nom }}</td>
                        <td>{{ \App\Models\TypeCotisation::frequences()[$type->frequence] }}</td>
                        <td>{{ number_format($type->montant_standard, 0, ',', ' ') }} FCFA</td>
                        <td><span class="badge bg-secondary">{{ $type->periodes_count }}</span></td>
                        <td>
                            <span class="badge bg-{{ $type->actif ? 'success' : 'secondary' }}">
                                {{ $type->actif ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.cotisations.types.edit', $type) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.cotisations.types.destroy', $type) }}"
                                  class="d-inline" data-confirm="Supprimer ce type et toutes ses périodes ?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Aucun type de cotisation.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
