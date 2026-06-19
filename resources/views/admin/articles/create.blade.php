@extends('layouts.admin')

@section('title', 'Nouvel article')
@section('page-title', 'Nouvel article')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.articles._form')

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-faaci-navy">Créer l'article</button>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
