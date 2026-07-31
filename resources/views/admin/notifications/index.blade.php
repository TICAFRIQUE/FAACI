@extends('layouts.admin')
@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')

@php
    $typesConfig = [
        'projet_soumis'           => ['label' => 'Projet',        'couleur' => 'warning',   'icon' => 'bi-lightbulb'],
        'promesse_investissement'  => ['label' => 'Investissement','couleur' => 'success',   'icon' => 'bi-cash-stack'],
        'nouveau_don'             => ['label' => 'Don',            'couleur' => 'info',      'icon' => 'bi-gift'],
        'entreprise_soumise'      => ['label' => 'Entreprise',     'couleur' => 'primary',   'icon' => 'bi-building'],
        'offre_emploi_soumise'    => ['label' => 'Offre d\'emploi','couleur' => 'secondary', 'icon' => 'bi-briefcase'],
        'candidature_emploi'      => ['label' => 'Candidature',    'couleur' => 'primary',   'icon' => 'bi-person-check'],
        'candidature_competition' => ['label' => 'Compétition',    'couleur' => 'warning',   'icon' => 'bi-trophy'],
        'nouvelle_demande'        => ['label' => 'Adhésion',       'couleur' => 'danger',    'icon' => 'bi-person-plus'],
    ];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0">Historique de toutes vos notifications.</p>
        <p class="text-muted small mb-0">
            <i class="bi bi-info-circle me-1"></i>
            Les notifications lues sont conservées <strong>7 jours</strong> puis supprimées automatiquement.
            Les non-lues ne sont jamais supprimées automatiquement.
        </p>
    </div>
    @if (auth()->user()->unreadNotifications()->count() > 0)
        <form method="POST" action="{{ route('admin.notifications.tout-lire') }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-check2-all me-1"></i> Tout marquer comme lu
            </button>
        </form>
    @endif
</div>

{{-- Légende des étiquettes --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    @foreach ($typesConfig as $cfg)
        <span class="badge text-bg-{{ $cfg['couleur'] }} opacity-75" style="font-size:.75rem;font-weight:500;">
            <i class="bi {{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
        </span>
    @endforeach
</div>

<div class="card border-0 shadow-sm">
    @forelse ($notifications as $notif)
        @php
            $data    = $notif->data;
            $type    = $data['type'] ?? '';
            $cfg     = $typesConfig[$type] ?? ['label' => 'Système', 'couleur' => 'secondary', 'icon' => 'bi-bell'];
            $lue     = $notif->read_at !== null;
            $joursRestants = $lue ? 7 - (int) $notif->read_at->diffInDays(now()) : null;
        @endphp
        <div class="d-flex align-items-start gap-3 px-4 py-3 border-bottom {{ $lue ? '' : 'bg-light' }}">

            {{-- Icône type --}}
            <div class="flex-shrink-0 mt-1">
                <span class="badge text-bg-{{ $cfg['couleur'] }} d-flex align-items-center justify-content-center"
                      style="width:36px;height:36px;border-radius:8px;font-size:1rem;">
                    <i class="bi {{ $cfg['icon'] }}"></i>
                </span>
            </div>

            {{-- Contenu --}}
            <div class="flex-grow-1 min-w-0">

                {{-- Ligne 1 : étiquette + titre + horodatage --}}
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="badge text-bg-{{ $cfg['couleur'] }}" style="font-size:.7rem;">
                            {{ $cfg['label'] }}
                        </span>
                        <span class="fw-semibold {{ $lue ? 'text-muted' : 'text-dark' }}" style="font-size:.92rem;">
                            {{ $data['titre'] ?? '' }}
                        </span>
                        @if (!$lue)
                            <span class="badge bg-danger" style="font-size:.6rem;letter-spacing:.05em;">NON LUE</span>
                        @endif
                    </div>
                    <span class="text-muted flex-shrink-0" style="font-size:.75rem;">
                        {{ $notif->created_at->diffForHumans() }}
                    </span>
                </div>

                {{-- Ligne 2 : message --}}
                <div class="text-muted mt-1" style="font-size:.88rem;">{{ $data['message'] ?? '' }}</div>

                {{-- Ligne 3 : durée conservation --}}
                <div class="d-flex align-items-center gap-3 mt-2 flex-wrap">
                    @if ($lue)
                        @if ($joursRestants !== null && $joursRestants > 0)
                            <span class="text-muted" style="font-size:.72rem;">
                                <i class="bi bi-clock me-1"></i>Suppression dans {{ $joursRestants }} jour{{ $joursRestants > 1 ? 's' : '' }}
                            </span>
                        @elseif ($joursRestants !== null && $joursRestants <= 0)
                            <span class="text-danger" style="font-size:.72rem;">
                                <i class="bi bi-clock me-1"></i>Suppression imminente
                            </span>
                        @endif
                        <span class="text-muted" style="font-size:.72rem;">
                            <i class="bi bi-check2-all me-1"></i>Lu {{ $notif->read_at->diffForHumans() }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex-shrink-0 d-flex gap-2 ms-2 align-self-start mt-1">
                @if (!empty($data['url']))
                    <form method="POST" action="{{ route('admin.notifications.lue', $notif->id) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-faaci-navy" title="Voir le détail">
                            <i class="bi bi-arrow-right me-1"></i> Détail
                        </button>
                    </form>
                @endif
                @if (!$lue)
                    <form method="POST" action="{{ route('admin.notifications.lue-seulement', $notif->id) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Marquer comme lu sans naviguer">
                            <i class="bi bi-check-lg"></i>
                        </button>
                    </form>
                @endif
            </div>

        </div>
    @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-bell-slash fs-2 d-block mb-2 opacity-25"></i>
            Aucune notification.
        </div>
    @endforelse
</div>

<div class="mt-3">
    {{ $notifications->links() }}
</div>

@endsection
