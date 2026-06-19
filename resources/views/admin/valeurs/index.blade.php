@extends('layouts.admin')

@section('title', 'Valeurs')
@section('page-title', 'Valeurs')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">Gérez les valeurs affichées sur le site (page d'accueil et À propos).</p>
        <a href="{{ route('admin.valeurs.create') }}" class="btn btn-faaci-navy">
            <i class="bi bi-plus-lg me-1"></i> Nouvelle valeur
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:70px;">Icône</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th class="text-center" style="width:80px;">Ordre</th>
                        <th class="text-end" style="width:120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($valeurs as $valeur)
                        <tr>
                            <td>
                                <div class="text-faaci-steel fs-4"><i class="bi {{ $valeur->icone }}"></i></div>
                            </td>
                            <td class="fw-semibold">{{ $valeur->titre }}</td>
                            <td class="text-muted">{{ $valeur->description }}</td>
                            <td class="text-center">
                                <div class="d-flex flex-column gap-1 align-items-center">
                                    <form method="POST" action="{{ route('admin.valeurs.deplacer', $valeur) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="direction" value="haut">
                                        <button class="btn btn-sm btn-light py-0 px-1" @if ($loop->first) disabled @endif>
                                            <i class="bi bi-arrow-up"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.valeurs.deplacer', $valeur) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="direction" value="bas">
                                        <button class="btn btn-sm btn-light py-0 px-1" @if ($loop->last) disabled @endif>
                                            <i class="bi bi-arrow-down"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.valeurs.edit', $valeur) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.valeurs.destroy', $valeur) }}" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette valeur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Aucune valeur pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
