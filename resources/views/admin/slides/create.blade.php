@extends('layouts.admin')

@section('title', 'Nouvelle slide')
@section('page-title', 'Nouvelle slide')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.slides.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.slides._form')

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-faaci-navy">Créer la slide</button>
                    <a href="{{ route('admin.slides.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
