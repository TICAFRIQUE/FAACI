@extends('layouts.admin')
@section('title', 'Modifier ' . $type->nom)
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Modifier {{ $type->nom }}</h4>
    <a href="{{ route('admin.cotisations.types.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.cotisations.types.update', $type) }}">
                    @csrf @method('PUT')
                    @include('admin.cotisations.types._form')
                    <div class="d-flex gap-2 justify-content-end mt-3">
                        <a href="{{ route('admin.cotisations.types.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        <button type="submit" class="btn btn-faaci-navy">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
