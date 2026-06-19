@extends('layouts.public')

@section('title', 'Événements — FAACI')
@section('description', "Le calendrier public des événements de la Fondation AIESEC Alumni Côte d'Ivoire.")

@section('content')

<section class="page-header">
    <div class="container">
        <div class="page-header-breadcrumb mb-3">
            <a href="{{ route('accueil') }}">Accueil</a> / Événements
        </div>
        <h1 class="page-header-title">Événements</h1>
    </div>
</section>

<section class="section bg-white">
    <div class="container">
        @if ($evenements->isNotEmpty())
            <div class="row g-4">
                @foreach ($evenements as $evenement)
                    <div class="col-md-6 col-lg-4 fade-in">
                        @include('public.evenements._card', ['evenement' => $evenement])
                    </div>
                @endforeach
            </div>
            <div class="mt-5">
                {{ $evenements->links() }}
            </div>
        @else
            <p class="text-muted text-center mb-0">Aucun événement programmé pour le moment.</p>
        @endif
    </div>
</section>

@endsection
