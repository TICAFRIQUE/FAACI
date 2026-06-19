@extends('layouts.public')

@section('title', 'Actualités — FAACI')
@section('description', "Toutes les actualités et communiqués de la Fondation AIESEC Alumni Côte d'Ivoire.")

@section('content')

<section class="page-header">
    <div class="container">
        <div class="page-header-breadcrumb mb-3">
            <a href="{{ route('accueil') }}">Accueil</a> / Actualités
        </div>
        <h1 class="page-header-title">Actualités</h1>
    </div>
</section>

<section class="section bg-white">
    <div class="container">
        @if ($articles->isNotEmpty())
            <div class="row g-4">
                @foreach ($articles as $article)
                    <div class="col-md-6 col-lg-4 fade-in">
                        @include('public.actualites._card', ['article' => $article])
                    </div>
                @endforeach
            </div>
            <div class="mt-5">
                {{ $articles->links() }}
            </div>
        @else
            <p class="text-muted text-center mb-0">Aucune actualité publiée pour le moment.</p>
        @endif
    </div>
</section>

@endsection
