@extends('layouts.admin')

@section('title', 'Publier une offre')
@section('page-title', 'Publier une offre d\'emploi')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.emplois.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                <i class="bi bi-briefcase me-1 text-faaci-steel"></i> Nouvelle offre d'emploi
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.emplois.store') }}">
                    @csrf
                    @include('admin.emplois._form')
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-faaci-primary">
                            <i class="bi bi-check-lg me-1"></i> Publier l'offre
                        </button>
                        <a href="{{ route('admin.emplois.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
