@extends('layouts.admin')

@section('title', $evenement->titre)
@section('page-title', 'Détail événement')

@section('content')
<div class="mb-3 d-flex gap-2">
    <a href="{{ route('admin.evenements.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
    <a href="{{ route('admin.evenements.edit', $evenement) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-pencil me-1"></i> Modifier
    </a>
    <form method="POST" action="{{ route('admin.evenements.basculer', $evenement) }}" class="d-inline">
        @csrf @method('PATCH')
        <button type="submit" class="btn btn-sm {{ $evenement->statut === 'publie' ? 'btn-warning' : 'btn-success' }}">
            <i class="bi bi-{{ $evenement->statut === 'publie' ? 'eye-slash' : 'globe' }} me-1"></i>
            {{ $evenement->statut === 'publie' ? 'Dépublier' : 'Publier' }}
        </button>
    </form>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        @if ($evenement->image_url)
            <img src="{{ $evenement->image_url }}" alt="" class="img-fluid rounded-3 mb-4 w-100" style="max-height:280px;object-fit:cover;">
        @endif

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-2 mb-2">
                    <h4 class="fw-bold flex-grow-1 mb-0">{{ $evenement->titre }}</h4>
                    <span class="badge bg-light text-dark border flex-shrink-0">{{ $evenement->type_libelle }}</span>
                </div>
                <div class="d-flex flex-wrap gap-3 small text-muted mb-4">
                    <span><i class="bi bi-calendar me-1"></i>{{ $evenement->date_debut->translatedFormat('d F Y à H:i') }}</span>
                    @if ($evenement->date_fin)
                        <span><i class="bi bi-calendar-check me-1"></i>Fin : {{ $evenement->date_fin->translatedFormat('d F Y à H:i') }}</span>
                    @endif
                    @if ($evenement->lieu)
                        <span><i class="bi bi-geo-alt me-1"></i>{{ $evenement->lieu }}</span>
                    @endif
                    @if ($evenement->lien_visio)
                        <a href="{{ $evenement->lien_visio }}" target="_blank" class="text-faaci-steel">
                            <i class="bi bi-camera-video me-1"></i>Lien visio
                        </a>
                    @endif
                </div>
                <p style="line-height:1.8;white-space:pre-line;">{{ $evenement->description }}</p>
            </div>
        </div>

        {{-- Liste des inscrits --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold pt-3 pb-2 border-bottom">
                <i class="bi bi-people me-1 text-faaci-steel"></i>
                Inscrits ({{ $evenement->inscriptions->where('statut','!=','annule')->count() }}
                @if ($evenement->capacite_max) / {{ $evenement->capacite_max }} @endif)
            </div>
            <div class="card-body p-0">
                @forelse ($evenement->inscriptions->where('statut','!=','annule') as $ins)
                    <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 text-white fw-bold"
                             style="width:32px;height:32px;background:var(--faaci-navy);font-size:0.75rem;">
                            {{ mb_strtoupper(mb_substr($ins->utilisateur->prenom,0,1)) }}{{ mb_strtoupper(mb_substr($ins->utilisateur->nom,0,1)) }}
                        </div>
                        <span class="small fw-medium flex-grow-1">{{ $ins->utilisateur->nom_complet }}</span>
                        <span class="badge {{ $ins->statut === 'confirme' ? 'bg-success' : 'bg-warning text-dark' }} small">
                            {{ $ins->statut === 'confirme' ? 'Confirmé' : 'Inscrit' }}
                        </span>
                        <span class="text-muted small">{{ $ins->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-muted small p-3 mb-0">Aucun inscrit.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <dl class="mb-0">
                    <dt class="text-muted small fw-normal">Statut</dt>
                    <dd class="fw-medium mb-3">
                        <span class="badge {{ $evenement->statut === 'publie' ? 'bg-success' : 'bg-secondary' }} fs-6">
                            {{ $evenement->statut === 'publie' ? 'Publié' : 'Brouillon' }}
                        </span>
                    </dd>

                    <dt class="text-muted small fw-normal">Visibilité</dt>
                    <dd class="mb-3">
                        @if ($evenement->est_public)
                            <span class="badge bg-info"><i class="bi bi-globe me-1"></i>Site public</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-lock me-1"></i>Membres seulement</span>
                        @endif
                    </dd>

                    <dt class="text-muted small fw-normal">Capacité</dt>
                    <dd class="mb-3">
                        {{ $evenement->capacite_max ? $evenement->capacite_max.' places' : 'Illimitée' }}
                    </dd>

                    <dt class="text-muted small fw-normal">Inscrits actifs</dt>
                    <dd class="mb-0 fw-bold text-faaci-navy fs-5">
                        {{ $evenement->inscriptions->whereIn('statut',['inscrit','confirme'])->count() }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
