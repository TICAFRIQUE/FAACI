@php
    $couleursType ??= [
        'reunion' => 'rgba(74,127,165,0.95)',
        'pitch' => 'rgba(245,158,11,0.95)',
        'webinaire' => 'rgba(13,31,60,0.95)',
        'networking' => 'rgba(74,127,165,0.95)',
        'ag' => 'rgba(13,31,60,0.95)',
    ];
    $mois = \Illuminate\Support\Str::ucfirst(str_replace('.', '', $evenement->date_debut->translatedFormat('M')));
    $fondEvenement = $evenement->image_url
        ? "url('{$evenement->image_url}')"
        : 'linear-gradient(135deg, var(--faaci-navy), var(--faaci-steel))';
@endphp
<a href="{{ route('evenements.show', $evenement) }}" class="event-card">
    <div class="event-image" style="background-image: {{ $fondEvenement }}; background-size: cover; background-position: center;">
        <div class="event-date-badge">
            <div class="event-date-day">{{ $evenement->date_debut->format('d') }}</div>
            <div class="event-date-month">{{ $mois }}</div>
        </div>
        <div class="event-type-badge" style="background: {{ $couleursType[$evenement->type] ?? 'rgba(74,127,165,0.95)' }};">{{ $evenement->type_libelle }}</div>
    </div>
    <h3 class="event-title">{{ $evenement->titre }}</h3>
    <p class="event-desc line-clamp-2">{{ strip_tags($evenement->description) }}</p>
    <div class="event-meta">
        <span><i class="bi bi-clock"></i> {{ $evenement->date_debut->format('H\hi') }}</span>
        @if ($evenement->lieu)
            <span><i class="bi bi-geo-alt"></i> {{ $evenement->lieu }}</span>
        @elseif ($evenement->lien_visio)
            <span><i class="bi bi-camera-video"></i> En ligne</span>
        @endif
    </div>
</a>
