@extends('layouts.admin')

@section('title', 'Nouvelle activité')
@section('page-title', 'Nouvelle activité')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.activites.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="card border-0 shadow-sm" style="max-width:720px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.activites.store') }}">
            @csrf
            @include('admin.activites._form')
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-faaci-navy">
                    <i class="bi bi-check-lg me-1"></i> Créer l'activité
                </button>
                <a href="{{ route('admin.activites.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
