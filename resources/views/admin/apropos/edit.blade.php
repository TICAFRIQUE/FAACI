@extends('layouts.admin')

@section('title', 'À propos')
@section('page-title', 'À propos')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <p class="text-muted mb-0">Modifiez le contenu des sous-pages Mission, Vision et Histoire.</p>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.valeurs.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-gem me-1"></i> Gérer les valeurs
            </a>
            <a href="{{ route('admin.equipe.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-people me-1"></i> Gérer l'équipe dirigeante
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.apropos.update') }}">
        @csrf
        @method('PUT')

        @foreach ($groupes as $cle => $libelle)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold text-faaci-navy">{{ $libelle }}</div>
                <div class="card-body">
                    <div class="row g-3">
                        @forelse ($contenus[$cle] ?? [] as $contenu)
                            @include('admin.contenus._champ', ['contenu' => $contenu])
                        @empty
                            <p class="text-muted mb-0">Aucun contenu configuré pour cette section.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-faaci-navy">
            <i class="bi bi-check-lg me-1"></i> Enregistrer les modifications
        </button>
    </form>
@endsection
