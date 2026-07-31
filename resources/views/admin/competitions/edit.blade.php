@extends('layouts.admin')

@section('title', 'Modifier ' . $competition->titre)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Modifier — {{ $competition->titre }}</h4>
    <a href="{{ route('admin.competitions.show', $competition) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <form method="POST" action="{{ route('admin.competitions.update', $competition) }}">
            @csrf @method('PUT')
            @include('admin.competitions._form')
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-faaci-primary">
                    <i class="bi bi-check-lg me-1"></i> Enregistrer
                </button>
                <a href="{{ route('admin.competitions.show', $competition) }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
