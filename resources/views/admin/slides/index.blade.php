@extends('layouts.admin')

@section('title', 'Slider (Hero)')
@section('page-title', 'Slider (Hero)')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">Gérez les slides affichées en haut de la page d'accueil.</p>
        <a href="{{ route('admin.slides.create') }}" class="btn btn-faaci-navy">
            <i class="bi bi-plus-lg me-1"></i> Nouvelle slide
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:90px;">Image</th>
                        <th>Titre</th>
                        <th>Sous-titre</th>
                        <th class="text-center" style="width:80px;">Ordre</th>
                        <th class="text-center" style="width:110px;">Statut</th>
                        <th class="text-end" style="width:140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($slides as $slide)
                        <tr>
                            <td>
                                @if ($slide->image_url)
                                    <img src="{{ $slide->image_url }}" alt="" class="rounded" style="width:64px; height:40px; object-fit:cover;">
                                @else
                                    <div class="bg-faaci-gray rounded d-flex align-items-center justify-content-center" style="width:64px; height:40px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $slide->titre }}</td>
                            <td class="text-muted">{{ $slide->sous_titre }}</td>
                            <td class="text-center">
                                <div class="d-flex flex-column gap-1 align-items-center">
                                    <form method="POST" action="{{ route('admin.slides.deplacer', $slide) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="direction" value="haut">
                                        <button class="btn btn-sm btn-light py-0 px-1" @if ($loop->first) disabled @endif>
                                            <i class="bi bi-arrow-up"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.slides.deplacer', $slide) }}">
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
                                <form method="POST" action="{{ route('admin.slides.basculer', $slide) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $slide->actif ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $slide->actif ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.slides.edit', $slide) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.slides.destroy', $slide) }}" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette slide ?');">
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
                            <td colspan="6" class="text-center text-muted py-4">Aucune slide pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
