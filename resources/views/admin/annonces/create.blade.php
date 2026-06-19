@extends('layouts.admin')
@section('title', 'Nouvelle annonce')
@section('page-title', 'Nouvelle annonce')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.annonces.store') }}">
                @csrf
                @include('admin.annonces._form')
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-faaci-navy">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer
                    </button>
                    <a href="{{ route('admin.annonces.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
