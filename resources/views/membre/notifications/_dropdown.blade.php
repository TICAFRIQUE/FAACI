@php
    $typesNotifs = [
        'evenement'   => ['icon' => 'bi-calendar-event', 'couleur' => 'info',    'label' => 'Événement'],
        'competition' => ['icon' => 'bi-trophy',         'couleur' => 'warning', 'label' => 'Compétition'],
        'cotisation'  => ['icon' => 'bi-wallet2',        'couleur' => 'danger',  'label' => 'Cotisation'],
        'info'        => ['icon' => 'bi-info-circle',    'couleur' => 'secondary','label' => 'Info'],
    ];
@endphp

@forelse ($annonces as $annonce)
    @php $cfg = $annonce->type_config; @endphp
    <a href="{{ route('membre.notifications.index') }}"
       class="d-flex gap-2 px-3 py-2 text-decoration-none border-bottom"
       style="background:#f8f9ff;">
        <i class="bi {{ $cfg['icone'] }} text-{{ $cfg['couleur'] }} mt-1 flex-shrink-0"></i>
        <div class="overflow-hidden flex-grow-1">
            <div class="d-flex align-items-center gap-1 mb-1">
                <span class="badge text-bg-{{ $cfg['couleur'] }}" style="font-size:.6rem;">Annonce</span>
            </div>
            <div class="small fw-semibold text-dark text-truncate">{{ $annonce->titre }}</div>
            <div class="text-muted" style="font-size:.72rem;">{{ $annonce->publiee_at->diffForHumans() }}</div>
        </div>
    </a>
@endforelse

@forelse ($notifications as $notif)
    @php
        $type = $notif->data['type'] ?? 'info';
        $cfg  = $typesNotifs[$type] ?? $typesNotifs['info'];
    @endphp
    <form method="POST" action="{{ route('membre.notifications.lue', $notif->id) }}">
        @csrf @method('PATCH')
        <button type="submit"
                class="d-flex gap-2 px-3 py-2 w-100 text-start border-bottom"
                style="background:#eef2ff;border:none;border-bottom:1px solid #dee2e6;cursor:pointer;">
            <span class="badge text-bg-{{ $cfg['couleur'] }} flex-shrink-0 mt-1 d-flex align-items-center justify-content-center"
                  style="width:28px;height:28px;border-radius:6px;font-size:.85rem;">
                <i class="bi {{ $cfg['icon'] }}"></i>
            </span>
            <div class="flex-grow-1 overflow-hidden">
                <div class="d-flex align-items-center gap-1 mb-1">
                    <span class="badge text-bg-{{ $cfg['couleur'] }}" style="font-size:.6rem;">{{ $cfg['label'] }}</span>
                    <span class="badge bg-faaci-steel" style="font-size:.55rem;">Nouveau</span>
                </div>
                <div class="small fw-semibold text-dark" style="white-space:normal;line-height:1.3;">{{ $notif->data['message'] ?? '' }}</div>
                <div class="text-muted" style="font-size:.72rem;">{{ $notif->created_at->diffForHumans() }}</div>
            </div>
        </button>
    </form>
@empty
    @if ($annonces->isEmpty())
        <div class="text-center text-muted py-4 small">
            <i class="bi bi-bell-slash d-block fs-3 mb-1 opacity-50"></i>
            Aucune nouvelle notification
        </div>
    @endif
@endforelse
