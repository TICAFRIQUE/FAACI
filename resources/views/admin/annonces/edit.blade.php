@extends('layouts.admin')
@section('title', 'Modifier l\'annonce')
@section('page-title', 'Modifier l\'annonce')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.annonces.update', $annonce) }}">
                @csrf @method('PUT')
                @include('admin.annonces._form')
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-faaci-navy">
                        <i class="bi bi-check-lg me-1"></i> Mettre à jour
                    </button>
                    <a href="{{ route('admin.annonces.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
