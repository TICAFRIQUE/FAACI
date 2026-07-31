@extends('layouts.app')

@section('title', 'Mon profil')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mon profil</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Mon profil</h4>
    <a href="{{ route('membre.profil.edit') }}" class="btn btn-faaci-primary btn-sm">
        <i class="bi bi-pencil me-1"></i> Modifier
    </a>
</div>

<div class="row g-4">
    {{-- Colonne gauche : avatar + photo --}}
    <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-4">
                @if ($membre->photo_url)
                    <img src="{{ $membre->photo_url }}" alt="{{ $membre->nom_complet }}"
                         class="rounded-circle mb-3 shadow-sm"
                         style="width:100px;height:100px;object-fit:cover;">
                @else
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                         style="width:100px;height:100px;background:var(--faaci-navy);font-size:2rem;color:#fff;font-weight:700;">
                        {{ mb_strtoupper(mb_substr($membre->prenom,0,1)) }}{{ mb_strtoupper(mb_substr($membre->nom,0,1)) }}
                    </div>
                @endif

                <h6 class="fw-bold mb-0">{{ $membre->nom_complet }}</h6>
                @if ($membre->secteur)
                    <p class="text-muted small mb-2">{{ $membre->secteur }}</p>
                @endif
                @if ($membre->ville)
                    <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i>{{ $membre->ville }}</p>
                @endif

                {{-- Barre de complétion --}}
                <div class="mt-3">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Profil complété</span>
                        <span>{{ $membre->completeness }} %</span>
                    </div>
                    <div class="progress" style="height:6px;">
                        <div class="progress-bar" style="width:{{ $membre->completeness }}%;background:var(--faaci-steel);"></div>
                    </div>
                </div>

                {{-- Upload photo --}}
                <div class="mt-3">
                    <form method="POST" action="{{ route('membre.profil.photo') }}" enctype="multipart/form-data" id="formPhoto">
                        @csrf
                        <label for="photoInput" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="bi bi-camera me-1"></i>
                            {{ $membre->photo_url ? 'Changer la photo' : 'Ajouter une photo' }}
                        </label>
                        <input type="file" id="photoInput" name="photo" accept="image/*" class="d-none"
                               onchange="document.getElementById('formPhoto').submit()">
                    </form>
                    @if ($membre->photo_url)
                        <form method="POST" action="{{ route('membre.profil.photo.delete') }}" class="mt-1"
                              data-confirm="Supprimer votre photo de profil ?">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                <i class="bi bi-trash me-1"></i> Supprimer la photo
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Colonne droite : infos --}}
    <div class="col-md-8 col-lg-9">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold border-bottom-0 pt-3">
                <i class="bi bi-person me-1 text-faaci-steel"></i> Informations personnelles
            </div>
            <div class="card-body pt-0">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label text-muted small mb-1">Prénom</label>
                        <p class="mb-0 fw-medium">{{ $membre->prenom ?: '—' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small mb-1">Nom</label>
                        <p class="mb-0 fw-medium">{{ $membre->nom ?: '—' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small mb-1">Adresse e-mail</label>
                        <p class="mb-0 fw-medium">{{ $membre->email }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small mb-1">Téléphone</label>
                        <p class="mb-0 fw-medium">{{ $membre->telephone ?: '—' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small mb-1">Ville</label>
                        <p class="mb-0 fw-medium">{{ $membre->ville ?: '—' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small mb-1">Promotion AIESEC</label>
                        <p class="mb-0 fw-medium">{{ $membre->promotion_aiesec ?: '—' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small mb-1">Comité local</label>
                        <p class="mb-0 fw-medium">{{ $membre->comite_local ?: '—' }}</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small mb-1">Secteur d'activité</label>
                        <p class="mb-0 fw-medium">{{ $membre->secteur ?: '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if ($membre->bio)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold border-bottom-0 pt-3">
                    <i class="bi bi-file-text me-1 text-faaci-steel"></i> Biographie
                </div>
                <div class="card-body pt-0">
                    <div class="rich-content mb-0">{!! $membre->bio !!}</div>
                </div>
            </div>
        @endif

        @if ($membre->competences)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold border-bottom-0 pt-3">
                    <i class="bi bi-tags me-1 text-faaci-steel"></i> Compétences
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($membre->competences as $comp)
                            <span class="badge rounded-pill" style="background:rgba(74,127,165,0.12);color:var(--faaci-steel);font-weight:500;font-size:0.82rem;">
                                {{ $comp }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
