@extends('layouts.admin')

@section('title', 'Équipe dirigeante')
@section('page-title', 'Équipe dirigeante')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">Gérez les membres de l'équipe dirigeante affichés sur le site.</p>
        <a href="{{ route('admin.equipe.create') }}" class="btn btn-faaci-navy">
            <i class="bi bi-plus-lg me-1"></i> Nouveau membre
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:70px;">Photo</th>
                        <th>Nom</th>
                        <th>Fonction</th>
                        <th class="text-center" style="width:80px;">Ordre</th>
                        <th class="text-center" style="width:110px;">Statut</th>
                        <th class="text-end" style="width:140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($membres as $membre)
                        <tr>
                            <td>
                                @if ($membre->photo_url)
                                    <img src="{{ $membre->photo_url }}" alt="" class="rounded-circle" style="width:44px; height:44px; object-fit:cover;">
                                @else
                                    <div class="bg-faaci-gray rounded-circle d-flex align-items-center justify-content-center" style="width:44px; height:44px;">
                                        <i class="bi bi-person text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $membre->nom_complet }}</td>
                            <td class="text-muted">{{ $membre->fonction }}</td>
                            <td class="text-center">
                                <div class="d-flex flex-column gap-1 align-items-center">
                                    <form method="POST" action="{{ route('admin.equipe.deplacer', $membre) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="direction" value="haut">
                                        <button class="btn btn-sm btn-light py-0 px-1" @if ($loop->first) disabled @endif>
                                            <i class="bi bi-arrow-up"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.equipe.deplacer', $membre) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="direction" value="bas">
                                        <button class="btn btn-sm btn-light py-0 px-1" @if ($loop->last) disabled @endif>
                                            <i class="bi bi-arrow-down"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('admin.equipe.basculer', $membre) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $membre->actif ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $membre->actif ? 'Visible' : 'Masqué' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.equipe.edit', $membre) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.equipe.destroy', $membre) }}" class="d-inline"
                                      onsubmit="return confirm('Supprimer ce membre ?');">
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
                            <td colspan="6" class="text-center text-muted py-4">Aucun membre pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
