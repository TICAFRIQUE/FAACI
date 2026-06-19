@extends('layouts.admin')

@section('title', 'Nouveau membre')
@section('page-title', 'Nouveau membre de l\'équipe')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.equipe.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.equipe._form')

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-faaci-navy">Ajouter le membre</button>
                    <a href="{{ route('admin.equipe.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
