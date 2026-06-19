@extends('layouts.app')

@section('title', 'Statut de mon compte')

@php
    $user = auth()->user();

    $messages = [
        'en_attente' => [
            'icon' => 'bi-hourglass-split',
            'titre' => 'Demande en cours d\'examen',
            'texte' => 'Votre demande d\'adhésion a bien été reçue. Un administrateur va l\'examiner et vous recevrez un e-mail dès que votre compte sera activé.',
        ],
        'suspendu' => [
            'icon' => 'bi-pause-circle',
            'titre' => 'Compte suspendu',
            'texte' => 'Votre compte a été temporairement suspendu.',
        ],
        'inactif' => [
            'icon' => 'bi-slash-circle',
            'titre' => 'Compte inactif',
            'texte' => 'Votre compte est actuellement inactif. Contactez un administrateur pour le réactiver.',
        ],
        'rejete' => [
            'icon' => 'bi-x-circle',
            'titre' => 'Demande refusée',
            'texte' => 'Votre demande d\'adhésion a été refusée.',
        ],
        'actif' => [
            'icon' => 'bi-check-circle',
            'titre' => 'Compte actif',
            'texte' => 'Votre compte est actif.',
        ],
    ];

    $info = $messages[$user->statut] ?? $messages['en_attente'];
@endphp

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5 text-center">
                    <i class="bi {{ $info['icon'] }} display-4 text-faaci-steel mb-3"></i>
                    <h1 class="h4 fw-bold text-faaci-navy mb-2">{{ $info['titre'] }}</h1>
                    <p class="text-muted mb-0">{{ $info['texte'] }}</p>

                    @if ($user->statut === 'suspendu' && $user->motif_suspension)
                        <div class="alert alert-warning mt-4 text-start">
                            <strong>Motif :</strong> {{ $user->motif_suspension }}
                        </div>
                    @endif

                    @if ($user->statut === 'rejete' && $user->motif_rejet)
                        <div class="alert alert-danger mt-4 text-start">
                            <strong>Motif :</strong> {{ $user->motif_rejet }}
                        </div>
                    @endif

                    @if ($user->statut === 'actif')
                        <a href="{{ $user->hasAnyRole(['admin', 'super_admin']) ? route('admin.dashboard') : route('dashboard') }}"
                           class="btn btn-faaci-navy mt-4">
                            Accéder à mon espace
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
