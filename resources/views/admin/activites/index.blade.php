@extends('layouts.admin')

@section('title', 'Activités')
@section('page-title', 'Activités — Domaines d\'action')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted mb-0">Gérez les domaines d'action affichés sur le site vitrine (page Activités et section Accueil).</p>
    <a href="{{ route('admin.activites.create') }}" class="btn btn-faaci-navy">
        <i class="bi bi-plus-lg me-1"></i> Nouvelle activité
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:60px;">Icône</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th class="text-center" style="width:80px;">Visible</th>
                    <th class="text-center" style="width:80px;">Ordre</th>
                    <th class="text-end" style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($activites as $activite)
                    <tr class="{{ $activite->actif ? '' : 'table-secondary opacity-60' }}">
                        <td>
                            <div class="text-faaci-steel fs-4"><i class="bi {{ $activite->icone }}"></i></div>
                        </td>
                        <td class="fw-semibold">{{ $activite->titre }}</td>
                        <td class="text-muted small">{{ Str::limit($activite->description, 80) }}</td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('admin.activites.basculer', $activite) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $activite->actif ? 'btn-success' : 'btn-outline-secondary' }}"
                                        title="{{ $activite->actif ? 'Masquer' : 'Afficher' }}">
                                    <i class="bi {{ $activite->actif ? 'bi-eye' : 'bi-eye-slash' }}"></i>
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <div class="d-flex flex-column gap-1 align-items-center">
                                <form method="POST" action="{{ route('admin.activites.deplacer', $activite) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="direction" value="haut">
                                    <button class="btn btn-sm btn-light py-0 px-1" @if ($loop->first) disabled @endif>
                                        <i class="bi bi-arrow-up"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.activites.deplacer', $activite) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="direction" value="bas">
                                    <button class="btn btn-sm btn-light py-0 px-1" @if ($loop->last) disabled @endif>
                                        <i class="bi bi-arrow-down"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.activites.edit', $activite) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.activites.destroy', $activite) }}" class="d-inline"
                                  data-confirm="Supprimer cette activité ?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucune activité pour le moment.
                            <a href="{{ route('admin.activites.create') }}">Créer la première</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
