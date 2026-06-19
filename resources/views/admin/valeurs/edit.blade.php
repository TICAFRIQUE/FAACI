@extends('layouts.admin')

@section('title', 'Modifier la valeur')
@section('page-title', 'Modifier la valeur')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.valeurs.update', $valeur) }}">
                @csrf
                @method('PUT')
                @include('admin.valeurs._form')

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-faaci-navy">Enregistrer</button>
                    <a href="{{ route('admin.valeurs.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
