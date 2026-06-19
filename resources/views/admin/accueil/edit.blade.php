@extends('layouts.admin')

@section('title', "Page d'accueil")
@section('page-title', "Page d'accueil")

@section('content')
    <p class="text-muted mb-4">Modifiez les textes et chiffres clés affichés sur la page d'accueil du site vitrine.</p>

    <form method="POST" action="{{ route('admin.accueil.update') }}" enctype="multipart/form-data">
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
