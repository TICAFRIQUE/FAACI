@extends('layouts.admin')

@section('title', 'Modifier le membre')
@section('page-title', 'Modifier le membre de l\'équipe')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.equipe.update', $membre) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.equipe._form')

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-faaci-navy">Enregistrer</button>
                    <a href="{{ route('admin.equipe.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
