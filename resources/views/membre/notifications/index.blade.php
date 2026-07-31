@extends('layouts.app')
@section('title', 'Notifications')
@section('breadcrumb')
    <li class="breadcrumb-item active">Notifications</li>
@endsection

@section('content')

@php
    $typesNotifs = [
        'evenement'   => ['label' => 'Événement',   'couleur' => 'info',    'icon' => 'bi-calendar-event'],
        'competition' => ['label' => 'Compétition',  'couleur' => 'warning', 'icon' => 'bi-trophy'],
        'cotisation'  => ['label' => 'Cotisation',   'couleur' => 'danger',  'icon' => 'bi-wallet2'],
        'info'        => ['label' => 'Information',  'couleur' => 'secondary','icon' => 'bi-info-circle'],
    ];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-semibold mb-0">Notifications & Annonces</h5>
        <p class="text-muted small mb-0">
            <i class="bi bi-clock me-1"></i>
            Les notifications lues sont conservées <strong>7 jours</strong> puis supprimées.
            Les non-lues restent indéfiniment.
        </p>
    </div>
</div>

{{-- ── Annonces admin ── --}}
@if ($annonces->isNotEmpty())
    <div class="d-flex align-items-center gap-2 mb-3">
        <h6 class="text-muted text-uppercase small fw-semibold mb-0">
            <i class="bi bi-megaphone me-1"></i> Annonces de l'administration
        </h6>
        <span class="badge bg-secondary fw-normal" style="font-size:.7rem;">{{ $annonces->count() }}</span>
    </div>
    <div class="d-flex flex-column gap-3 mb-5">
        @foreach ($annonces as $annonce)
            @php $cfg = $annonce->type_config; @endphp
            <div class="card border-0 shadow-sm border-start border-4 border-{{ $cfg['couleur'] }}">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        <i class="bi {{ $cfg['icone'] }} text-{{ $cfg['couleur'] }} fs-4 flex-shrink-0 mt-1"></i>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
                                <h6 class="fw-semibold mb-1">{{ $annonce->titre }}</h6>
                                <span class="text-muted small flex-shrink-0">{{ $annonce->publiee_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-muted" style="font-size:.9rem;">{!! $annonce->contenu !!}</div>
                            @if ($annonce->expire_at)
                                <div class="text-muted mt-1" style="font-size:.75rem;">
                                    <i class="bi bi-clock me-1"></i>Expire le {{ $annonce->expire_at->format('d/m/Y') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- ── Notifications événements / compétitions / cotisations ── --}}
<div class="d-flex align-items-center gap-2 mb-3 {{ $annonces->isNotEmpty() ? '' : '' }}">
    <h6 class="text-muted text-uppercase small fw-semibold mb-0">
        <i class="bi bi-bell me-1"></i> Notifications
    </h6>
    @if ($notifications->isNotEmpty())
        <span class="badge bg-secondary fw-normal" style="font-size:.7rem;">{{ $notifications->count() }}</span>
    @endif
</div>

@if ($notifications->isEmpty() && $annonces->isEmpty())
    <div class="text-center text-muted py-5">
        <i class="bi bi-bell-slash fs-1 d-block mb-2 opacity-50"></i>
        <p class="mb-0">Aucune notification pour le moment.</p>
    </div>
@elseif ($notifications->isEmpty())
    <div class="text-muted small fst-italic">Aucune notification.</div>
@else
    <div class="d-flex flex-column gap-3">
        @foreach ($notifications as $notif)
            @php
                $lue   = !is_null($notif->read_at);
                $type  = $notif->data['type'] ?? 'info';
                $cfg   = $typesNotifs[$type] ?? $typesNotifs['info'];

                $statut      = $notif->data['statut'] ?? null;
                $borderColor = match($statut) {
                    'gagnante'     => 'success',
                    'selectionnee' => 'info',
                    'eliminee'     => 'danger',
                    default        => ($lue ? 'light' : $cfg['couleur']),
                };

                $joursRestants = $lue ? max(0, 7 - (int) $notif->read_at->diffInDays(now())) : null;
                $url = $notif->data['url'] ?? null;
            @endphp

            <div class="card border-0 shadow-sm border-start border-3 border-{{ $borderColor }}"
                 style="{{ $lue ? 'opacity:.8;' : '' }}">
                <div class="card-body py-3 px-3">
                    <div class="d-flex align-items-start gap-3">

                        {{-- Icône --}}
                        <span class="badge text-bg-{{ $cfg['couleur'] }} flex-shrink-0 mt-1 d-flex align-items-center justify-content-center"
                              style="width:34px;height:34px;border-radius:8px;font-size:1rem;">
                            <i class="bi {{ $cfg['icon'] }}"></i>
                        </span>

                        {{-- Contenu --}}
                        <div class="flex-grow-1">
                            {{-- Étiquette + état --}}
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <span class="badge text-bg-{{ $cfg['couleur'] }}" style="font-size:.68rem;">
                                    {{ $cfg['label'] }}
                                </span>
                                @if (!$lue)
                                    <span class="badge bg-faaci-steel" style="font-size:.62rem;">Nouveau</span>
                                @endif
                            </div>

                            {{-- Message --}}
                            <div class="{{ $lue ? 'text-muted' : 'fw-medium text-dark' }}" style="font-size:.9rem;">
                                {{ $notif->data['message'] ?? '' }}
                            </div>

                            {{-- Note jury (compétitions) --}}
                            @if (!empty($notif->data['note_jury']))
                                <div class="text-muted fst-italic mt-1" style="font-size:.8rem;">
                                    <i class="bi bi-chat-quote me-1"></i>{{ $notif->data['note_jury'] }}
                                </div>
                            @endif

                            {{-- Méta : date + durée restante --}}
                            <div class="d-flex align-items-center gap-3 mt-2 flex-wrap">
                                <span class="text-muted" style="font-size:.75rem;">
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                                @if ($lue)
                                    <span class="text-muted" style="font-size:.72rem;">
                                        <i class="bi bi-check2-all me-1"></i>Vue {{ $notif->read_at->diffForHumans() }}
                                    </span>
                                    @if ($joursRestants > 0)
                                        <span class="text-muted" style="font-size:.72rem;">
                                            <i class="bi bi-clock me-1"></i>Suppression dans {{ $joursRestants }} j.
                                        </span>
                                    @elseif ($joursRestants === 0)
                                        <span class="text-danger" style="font-size:.72rem;">
                                            <i class="bi bi-clock me-1"></i>Suppression imminente
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- Lien détail --}}
                        @if ($url)
                            <a href="{{ $url }}" class="btn btn-sm btn-faaci-primary flex-shrink-0 align-self-start mt-1">
                                <i class="bi bi-arrow-right me-1"></i> Détail
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
