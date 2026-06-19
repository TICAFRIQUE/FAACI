@extends('layouts.admin')

@section('title', 'Nouvel album')
@section('page-title', 'Nouvel album')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.galerie.store') }}">
                @csrf
                @include('admin.galerie._form')

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-faaci-navy">Créer l'album</button>
                    <a href="{{ route('admin.galerie.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
