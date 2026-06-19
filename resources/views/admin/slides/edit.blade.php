@extends('layouts.admin')

@section('title', 'Modifier la slide')
@section('page-title', 'Modifier la slide')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.slides.update', $slide) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.slides._form')

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-faaci-navy">Enregistrer</button>
                    <a href="{{ route('admin.slides.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
