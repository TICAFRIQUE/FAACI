@extends('layouts.app')

@section('title', 'Événements')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
<style>
    #calendrier-evenements {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.875rem;
    }
    .fc-event { cursor: pointer; border-radius: 4px !important; font-size: 0.78rem; }
    .fc-toolbar-title { font-family: 'Playfair Display', serif; font-size: 1.1rem !important; }
    .fc-button-primary {
        background-color: var(--faaci-navy) !important;
        border-color: var(--faaci-navy) !important;
    }
    .fc-button-primary:hover, .fc-button-primary:focus {
        background-color: var(--faaci-steel) !important;
        border-color: var(--faaci-steel) !important;
    }
    .fc-button-active {
        background-color: var(--faaci-steel) !important;
        border-color: var(--faaci-steel) !important;
    }
    /* Badge "inscrit" dans les événements du calendrier */
    .fc-event-inscrit { border-width: 2px !important; border-color: #ffc107 !important; }
</style>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item active">Événements</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">Événements</h4>
</div>

{{-- Onglets Vue --}}
<ul class="nav nav-tabs mb-4" id="tabVue" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-liste" data-bs-toggle="tab" data-bs-target="#pane-liste"
                type="button" role="tab">
            <i class="bi bi-list-ul me-1"></i> Liste
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-calendrier" data-bs-toggle="tab" data-bs-target="#pane-calendrier"
                type="button" role="tab">
            <i class="bi bi-calendar3 me-1"></i> Calendrier
        </button>
    </li>
</ul>

<div class="tab-content" id="tabVueContent">

    {{-- ═══════════════════════ VUE LISTE ═══════════════════════ --}}
    <div class="tab-pane fade show active" id="pane-liste" role="tabpanel">

        {{-- Filtres --}}
        <form method="GET" class="d-flex flex-wrap gap-2 mb-4">
            <select name="type" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
                <option value="">Tous les types</option>
                @foreach (\App\Models\Evenement::TYPES as $val => $lib)
                    <option value="{{ $val }}" {{ request('type') === $val ? 'selected' : '' }}>{{ $lib }}</option>
                @endforeach
            </select>
            <select name="periode" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
                <option value="a_venir" {{ request('periode','a_venir')==='a_venir'?'selected':'' }}>À venir</option>
                <option value="passes"  {{ request('periode')==='passes'?'selected':'' }}>Passés</option>
            </select>
            @if(request()->hasAny(['type','periode']))
                <a href="{{ route('membre.evenements.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i>Réinitialiser
                </a>
            @endif
        </form>

        @forelse ($evenements as $ev)
            @php
                $inscrip = $monInscription[$ev->id] ?? null;
                $passe   = $ev->date_debut->isPast();
                $complet = $ev->capacite_max && $ev->nb_inscrits >= $ev->capacite_max;
            @endphp
            <div class="card border-0 shadow-sm mb-3">
                <div class="row g-0">
                    @if ($ev->image_url)
                        <div class="col-md-3">
                            <img src="{{ $ev->image_url }}" alt="" class="img-fluid rounded-start h-100"
                                 style="object-fit:cover;max-height:160px;width:100%;">
                        </div>
                    @endif
                    <div class="{{ $ev->image_url ? 'col-md-9' : 'col-12' }}">
                        <div class="card-body p-3 p-md-4 d-flex flex-column h-100">
                            <div class="d-flex align-items-start gap-2 mb-1">
                                <span class="badge bg-light text-dark border">{{ $ev->type_libelle }}</span>
                                @if ($ev->est_public)
                                    <span class="badge bg-info"><i class="bi bi-globe me-1"></i>Public</span>
                                @endif
                                @if ($inscrip && $inscrip->statut !== 'annule')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-check-circle me-1"></i>Inscrit(e)</span>
                                @endif
                                @if ($passe)
                                    <span class="badge bg-secondary">Passé</span>
                                @elseif ($complet)
                                    <span class="badge bg-danger">Complet</span>
                                @endif
                            </div>
                            <h5 class="fw-bold mb-1">
                                <a href="{{ route('membre.evenements.show', $ev) }}"
                                   class="text-decoration-none text-dark">{{ $ev->titre }}</a>
                            </h5>
                            <div class="d-flex flex-wrap gap-3 small text-muted mb-2">
                                <span><i class="bi bi-calendar me-1"></i>{{ $ev->date_debut->translatedFormat('d F Y à H:i') }}</span>
                                @if ($ev->lieu)
                                    <span><i class="bi bi-geo-alt me-1"></i>{{ $ev->lieu }}</span>
                                @endif
                                @if ($ev->capacite_max)
                                    <span><i class="bi bi-people me-1"></i>{{ $ev->nb_inscrits }} / {{ $ev->capacite_max }} inscrits</span>
                                @else
                                    <span><i class="bi bi-people me-1"></i>{{ $ev->nb_inscrits }} inscrit(s)</span>
                                @endif
                            </div>
                            <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($ev->description, 120) }}</p>

                            <div class="d-flex gap-2 align-items-center mt-auto">
                                <a href="{{ route('membre.evenements.show', $ev) }}"
                                   class="btn btn-sm btn-outline-secondary">Voir détail</a>
                                @if (!$passe && !$inscrip)
                                    @if ($complet)
                                        <span class="btn btn-sm btn-outline-danger disabled">Complet</span>
                                    @else
                                        <form method="POST" action="{{ route('membre.evenements.inscrire', $ev) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-faaci-primary">
                                                <i class="bi bi-calendar-check me-1"></i> S'inscrire
                                            </button>
                                        </form>
                                    @endif
                                @elseif ($inscrip && $inscrip->statut !== 'annule')
                                    @if (!$passe)
                                        <form method="POST" action="{{ route('membre.evenements.desinscrire', $ev) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Se désinscrire de cet événement ?')">
                                                Se désinscrire
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-25"></i>
                Aucun événement à venir pour le moment.
            </div>
        @endforelse

        {{ $evenements->withQueryString()->links() }}
    </div>

    {{-- ═══════════════════════ VUE CALENDRIER ═══════════════════════ --}}
    <div class="tab-pane fade" id="pane-calendrier" role="tabpanel">
        {{-- Légende types --}}
        <div class="d-flex flex-wrap gap-2 mb-3 small">
            <span class="badge rounded-pill px-3 py-2" style="background:#0D1F3C;">Réunion</span>
            <span class="badge rounded-pill px-3 py-2" style="background:#4A7FA5;">Pitch</span>
            <span class="badge rounded-pill px-3 py-2" style="background:#2e7d32;">Webinaire</span>
            <span class="badge rounded-pill px-3 py-2" style="background:#e65100;">Networking</span>
            <span class="badge rounded-pill px-3 py-2" style="background:#6a1b9a;">Assemblée générale</span>
            <span class="badge rounded-pill px-3 py-2 bg-light text-dark border border-warning border-2">
                <i class="bi bi-check-circle text-warning me-1"></i>Inscrit(e)
            </span>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div id="calendrier-evenements"></div>
            </div>
        </div>
    </div>

</div>

{{-- Tooltip Bootstrap (pour FullCalendar) --}}
<div id="fc-tooltip" class="tooltip bs-tooltip-auto fade" role="tooltip" style="display:none;pointer-events:none;z-index:9999;">
    <div class="tooltip-arrow"></div>
    <div class="tooltip-inner text-start" id="fc-tooltip-content" style="max-width:240px;"></div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendrier-evenements');
    if (!calendarEl) return;

    const jsonUrl  = '{{ route('membre.evenements.calendrier-json') }}';
    const tooltip  = document.getElementById('fc-tooltip');
    const tooltipContent = document.getElementById('fc-tooltip-content');

    let calendar = null;

    // N'initialiser le calendrier qu'à l'activation de l'onglet (évite un rendu à taille 0)
    document.getElementById('tab-calendrier').addEventListener('shown.bs.tab', function () {
        if (calendar) { calendar.render(); return; }

        calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'fr',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left:   'prev,next today',
                center: 'title',
                right:  'dayGridMonth,timeGridWeek,listMonth',
            },
            buttonText: {
                today:     "Aujourd'hui",
                month:     'Mois',
                week:      'Semaine',
                list:      'Liste',
            },
            height: 'auto',
            events: {
                url: jsonUrl,
                method: 'GET',
                failure: function () {
                    alert('Impossible de charger les événements.');
                },
            },
            eventClassNames: function (info) {
                return info.event.extendedProps.inscrit ? ['fc-event-inscrit'] : [];
            },
            eventDidMount: function (info) {
                // Afficher un tooltip au survol
                info.el.addEventListener('mouseenter', function (e) {
                    const p = info.event.extendedProps;
                    let html = '<strong>' + info.event.title + '</strong><br>'
                             + '<span class="opacity-75">' + p.type_libelle + '</span>';
                    if (p.lieu) html += '<br><i class="bi bi-geo-alt me-1"></i>' + p.lieu;
                    if (p.inscrit) html += '<br><span class="text-warning"><i class="bi bi-check-circle me-1"></i>Vous êtes inscrit(e)</span>';
                    if (p.complet && !p.inscrit) html += '<br><span class="text-danger">Complet</span>';
                    tooltipContent.innerHTML = html;
                    tooltip.style.display = 'block';
                    positionTooltip(e);
                });
                info.el.addEventListener('mousemove', positionTooltip);
                info.el.addEventListener('mouseleave', function () {
                    tooltip.style.display = 'none';
                });
            },
            // Clic → aller sur la page de détail
            eventClick: function (info) {
                info.jsEvent.preventDefault();
                if (info.event.url) window.location.href = info.event.url;
            },
        });

        calendar.render();
    });

    function positionTooltip(e) {
        tooltip.style.left = (e.pageX + 12) + 'px';
        tooltip.style.top  = (e.pageY - 28) + 'px';
    }
});
</script>
@endpush
