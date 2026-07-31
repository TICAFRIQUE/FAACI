@extends('layouts.admin')

@section('title', 'Nouvelle compétition')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Nouvelle compétition</h4>
    <a href="{{ route('admin.competitions.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <form method="POST" action="{{ route('admin.competitions.store') }}">
            @csrf
            @include('admin.competitions._form')
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-faaci-primary">
                    <i class="bi bi-check-lg me-1"></i> Créer la compétition
                </button>
                <a href="{{ route('admin.competitions.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
