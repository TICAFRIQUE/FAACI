@extends('layouts.admin')

@section('title', 'Galerie')
@section('page-title', 'Galerie')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">Gérez les albums photo affichés sur le site public.</p>
        <a href="{{ route('admin.galerie.create') }}" class="btn btn-faaci-navy">
            <i class="bi bi-plus-lg me-1"></i> Nouvel album
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:90px;">Couverture</th>
                        <th>Titre</th>
                        <th class="text-center" style="width:90px;">Photos</th>
                        <th class="text-center" style="width:80px;">Ordre</th>
                        <th class="text-center" style="width:110px;">Statut</th>
                        <th class="text-end" style="width:140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($albums as $album)
                        <tr>
                            <td>
                                @if ($album->images->first()?->image_url)
                                    <img src="{{ $album->images->first()->image_url }}" alt="" class="rounded" style="width:64px; height:40px; object-fit:cover;">
                                @else
                                    <div class="bg-faaci-gray rounded d-flex align-items-center justify-content-center" style="width:64px; height:40px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $album->titre }}</td>
                            <td class="text-center text-muted">{{ $album->images_count }}</td>
                            <td class="text-center">
                                <div class="d-flex flex-column gap-1 align-items-center">
                                    <form method="POST" action="{{ route('admin.galerie.deplacer', $album) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="direction" value="haut">
                                        <button class="btn btn-sm btn-light py-0 px-1" @if ($loop->first) disabled @endif>
                                            <i class="bi bi-arrow-up"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.galerie.deplacer', $album) }}">
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
                                <form method="POST" action="{{ route('admin.galerie.basculer', $album) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $album->actif ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $album->actif ? 'Actif' : 'Inactif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.galerie.edit', $album) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.galerie.destroy', $album) }}" class="d-inline"
                                      onsubmit="return confirm('Supprimer cet album et toutes ses photos ?');">
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
                            <td colspan="6" class="text-center text-muted py-4">Aucun album pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
