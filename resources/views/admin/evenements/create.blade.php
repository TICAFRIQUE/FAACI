@extends('layouts.admin')

@section('title', 'Nouvel événement')
@section('page-title', 'Nouvel événement')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.evenements.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.evenements._form')

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-faaci-navy">Créer l'événement</button>
                    <a href="{{ route('admin.evenements.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
