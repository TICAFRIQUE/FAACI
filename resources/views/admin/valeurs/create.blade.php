@extends('layouts.admin')

@section('title', 'Nouvelle valeur')
@section('page-title', 'Nouvelle valeur')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.valeurs.store') }}">
                @csrf
                @include('admin.valeurs._form')

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-faaci-navy">Créer la valeur</button>
                    <a href="{{ route('admin.valeurs.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
