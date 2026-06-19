@extends('layouts.app')

@section('title', 'Offres d\'emploi')

@section('breadcrumb')
    <li class="breadcrumb-item active">Offres d'emploi</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">Offres d'emploi</h4>
    <a href="{{ route('membre.emplois.create') }}" class="btn btn-faaci-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Publier une offre
    </a>
</div>

{{-- Filtres --}}
<form method="GET" class="d-flex flex-wrap gap-2 mb-4">
    <select name="type" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
        <option value="">Tous les contrats</option>
        @foreach (\App\Models\OffreEmploi::TYPES_CONTRAT as $val => $lib)
            <option value="{{ $val }}" {{ request('type') === $val ? 'selected' : '' }}>{{ $lib }}</option>
        @endforeach
    </select>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher…"
           class="form-control form-control-sm" style="max-width:200px;">
    <button type="submit" class="btn btn-sm btn-outline-secondary">Filtrer</button>
    @if(request()->hasAny(['type','q']))
        <a href="{{ route('membre.emplois.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-x-lg me-1"></i>Réinitialiser
        </a>
    @endif
</form>

@forelse ($offres as $offre)
    @php
        $colors = ['cdi'=>'success','cdd'=>'primary','stage'=>'info','freelance'=>'warning','alternance'=>'secondary'];
        $dejaPostule = in_array($offre->id, $mesCandidatureIds);
    @endphp
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-start gap-2 mb-2 flex-wrap">
                <span class="badge bg-{{ $colors[$offre->type_contrat] ?? 'secondary' }}">{{ $offre->type_libelle }}</span>
                @if ($offre->est_expiree)
                    <span class="badge bg-secondary">Expirée</span>
                @endif
                @if ($dejaPostule)
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Candidature envoyée</span>
                @endif
            </div>
            <h5 class="fw-bold mb-1">
                <a href="{{ route('membre.emplois.show', $offre) }}" class="text-decoration-none text-dark">{{ $offre->titre }}</a>
            </h5>
            <div class="d-flex flex-wrap gap-3 small text-muted mb-2">
                <span><i class="bi bi-person me-1"></i>{{ $offre->auteur->nom_complet }}</span>
                @if ($offre->localisation)
                    <span><i class="bi bi-geo-alt me-1"></i>{{ $offre->localisation }}</span>
                @endif
                @if ($offre->salaire)
                    <span><i class="bi bi-cash me-1"></i>{{ $offre->salaire }}</span>
                @endif
                @if ($offre->date_expiration)
                    <span><i class="bi bi-clock me-1"></i>Expire le {{ $offre->date_expiration->translatedFormat('d M Y') }}</span>
                @endif
            </div>
            <p class="text-muted small mb-3">{{ Str::limit($offre->description, 150) }}</p>
            <div class="d-flex gap-2">
                <a href="{{ route('membre.emplois.show', $offre) }}" class="btn btn-sm btn-outline-secondary">Voir l'offre</a>
                @if (!$dejaPostule && !$offre->est_expiree && $offre->utilisateur_id !== auth()->id())
                    @if ($offre->lien_externe)
                        <a href="{{ $offre->lien_externe }}" target="_blank" class="btn btn-sm btn-faaci-primary">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Postuler
                        </a>
                    @else
                        <a href="{{ route('membre.emplois.show', $offre) }}#postuler" class="btn btn-sm btn-faaci-primary">
                            <i class="bi bi-send me-1"></i> Postuler
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-briefcase fs-1 d-block mb-2 opacity-25"></i>
        Aucune offre d'emploi disponible pour le moment.
        <div class="mt-3">
            <a href="{{ route('membre.emplois.create') }}" class="btn btn-faaci-primary btn-sm">
                Publier une offre
            </a>
        </div>
    </div>
@endforelse

{{ $offres->withQueryString()->links() }}
@endsection
