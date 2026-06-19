@extends('layouts.app')
@section('title', 'Notifications')
@section('breadcrumb')
    <li class="breadcrumb-item active">Notifications</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-semibold mb-0">Notifications & Annonces</h5>
    <form method="POST" action="{{ route('membre.notifications.tout-lire') }}">
        @csrf @method('PATCH')
        <button class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-check2-all me-1"></i> Tout marquer lu
        </button>
    </form>
</div>

{{-- ── Annonces admin ── --}}
@if ($annonces->isNotEmpty())
    <h6 class="text-muted text-uppercase small fw-semibold mb-3 mt-4">
        <i class="bi bi-megaphone me-1"></i> Annonces de l'administration
    </h6>
    <div class="d-flex flex-column gap-3 mb-4">
        @foreach ($annonces as $annonce)
            @php $cfg = $annonce->type_config; @endphp
            <div class="card border-0 shadow-sm border-start border-4 border-{{ $cfg['couleur'] }}">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        <i class="bi {{ $cfg['icone'] }} text-{{ $cfg['couleur'] }} fs-4 flex-shrink-0 mt-1"></i>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="fw-semibold mb-1">{{ $annonce->titre }}</h6>
                                <span class="text-muted small ms-3 flex-shrink-0">{{ $annonce->publiee_at->diffForHumans() }}</span>
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

{{-- ── Notifications événements ── --}}
<h6 class="text-muted text-uppercase small fw-semibold mb-3 mt-4">
    <i class="bi bi-bell me-1"></i> Notifications événements
</h6>

@if ($notifications->isEmpty())
    <div class="text-center text-muted py-5">
        <i class="bi bi-bell-slash fs-1 d-block mb-2 opacity-50"></i>
        <p>Aucune notification pour le moment.</p>
    </div>
@else
    <div class="d-flex flex-column gap-2">
        @foreach ($notifications as $notif)
            <div class="card border-0 shadow-sm {{ $notif->read_at ? '' : 'border-start border-3 border-faaci-steel' }}">
                <div class="card-body py-2 px-3 d-flex align-items-center gap-3">
                    <i class="bi bi-calendar-event text-faaci-steel fs-5 flex-shrink-0"></i>
                    <div class="flex-grow-1">
                        <div class="small fw-semibold {{ $notif->read_at ? 'text-muted' : 'text-dark' }}">
                            {{ $notif->data['message'] }}
                        </div>
                        <div class="text-muted" style="font-size:.75rem;">{{ $notif->created_at->diffForHumans() }}</div>
                    </div>
                    @if (isset($notif->data['url']))
                        <a href="{{ $notif->data['url'] }}" class="btn btn-sm btn-outline-secondary flex-shrink-0">
                            Voir <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    @endif
                    @if (!$notif->read_at)
                        <span class="badge bg-faaci-steel rounded-circle p-1 flex-shrink-0" style="width:8px;height:8px;"></span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-3">{{ $notifications->links() }}</div>
@endif
@endsection
