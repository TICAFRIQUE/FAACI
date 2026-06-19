@extends('layouts.app')

@section('title', $offre->titre)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.emplois.index') }}">Emplois</a></li>
    <li class="breadcrumb-item active text-truncate" style="max-width:200px;">{{ $offre->titre }}</li>
@endsection

@section('content')
<div class="mb-3">
    <a href="{{ route('membre.emplois.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

@php
    $colors = ['cdi'=>'success','cdd'=>'primary','stage'=>'info','freelance'=>'warning','alternance'=>'secondary'];
    $peutPostuler = !$maCandidature && !$offre->est_expiree && $offre->utilisateur_id !== auth()->id();
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-2 mb-2">
                    <h4 class="fw-bold flex-grow-1 mb-0">{{ $offre->titre }}</h4>
                    <span class="badge bg-{{ $colors[$offre->type_contrat] ?? 'secondary' }} flex-shrink-0">{{ $offre->type_libelle }}</span>
                </div>
                <div class="d-flex flex-wrap gap-3 small text-muted mb-4">
                    <span><i class="bi bi-person me-1"></i>{{ $offre->auteur->nom_complet }}</span>
                    @if ($offre->localisation)
                        <span><i class="bi bi-geo-alt me-1"></i>{{ $offre->localisation }}</span>
                    @endif
                    @if ($offre->salaire)
                        <span><i class="bi bi-cash me-1"></i>{{ $offre->salaire }}</span>
                    @endif
                    <span><i class="bi bi-clock me-1"></i>Publiée {{ $offre->created_at->diffForHumans() }}</span>
                    @if ($offre->date_expiration)
                        <span class="{{ $offre->est_expiree ? 'text-danger' : '' }}">
                            <i class="bi bi-calendar-x me-1"></i>
                            {{ $offre->est_expiree ? 'Expirée' : 'Expire le '.$offre->date_expiration->translatedFormat('d M Y') }}
                        </span>
                    @endif
                </div>

                <div class="rich-content">{!! $offre->description !!}</div>

                @if ($offre->competences_requises)
                    <hr>
                    <h6 class="fw-semibold mb-2">Compétences requises</h6>
                    <div class="rich-content text-muted small">{!! $offre->competences_requises !!}</div>
                @endif
            </div>
        </div>

        {{-- Section candidature --}}
        @if ($offre->lien_externe)
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <p class="text-muted small mb-3">Candidature via un lien externe.</p>
                    <a href="{{ $offre->lien_externe }}" target="_blank" class="btn btn-faaci-primary">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Postuler sur le site externe
                    </a>
                </div>
            </div>
        @elseif ($maCandidature)
            <div class="alert alert-success" id="postuler">
                <i class="bi bi-check-circle me-1"></i>
                Vous avez postulé à cette offre.
                @php
                    $sc = ['soumise'=>'warning','en_cours'=>'info','acceptee'=>'success','rejetee'=>'danger'];
                @endphp
                <span class="badge bg-{{ $sc[$maCandidature->statut] ?? 'secondary' }} ms-1">{{ $maCandidature->statut_libelle }}</span>
                @if ($maCandidature->note_recruteur)
                    <div class="mt-2 small">Note du recruteur : {{ $maCandidature->note_recruteur }}</div>
                @endif
            </div>
        @elseif ($peutPostuler)
            <div class="card border-0 shadow-sm" id="postuler">
                <div class="card-header bg-white fw-semibold pt-3 pb-2 border-bottom">
                    <i class="bi bi-send me-1 text-faaci-steel"></i> Postuler à cette offre
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('membre.emplois.postuler', $offre) }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="lettre_motivation" class="form-label">Lettre de motivation</label>
                            <textarea id="lettre_motivation" name="lettre_motivation" rows="5"
                                      class="form-control @error('lettre_motivation') is-invalid @enderror"
                                      placeholder="Présentez-vous et expliquez pourquoi vous postulez…">{{ old('lettre_motivation') }}</textarea>
                            @error('lettre_motivation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label for="cv" class="form-label">CV (PDF ou Word, max 5 Mo)</label>
                            <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx"
                                   class="form-control @error('cv') is-invalid @enderror">
                            @error('cv')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-faaci-primary">
                            <i class="bi bi-send me-1"></i> Envoyer ma candidature
                        </button>
                    </form>
                </div>
            </div>
        @elseif ($offre->est_expiree)
            <div class="alert alert-secondary">Cette offre est expirée.</div>
        @elseif ($offre->utilisateur_id === auth()->id())
            <div class="alert alert-info">C'est votre offre — vous ne pouvez pas y postuler.</div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <p class="small text-muted mb-1 fw-semibold">Publié par</p>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 text-white fw-bold"
                         style="width:34px;height:34px;background:var(--faaci-navy);font-size:0.75rem;">
                        {{ mb_strtoupper(mb_substr($offre->auteur->prenom,0,1)) }}{{ mb_strtoupper(mb_substr($offre->auteur->nom,0,1)) }}
                    </div>
                    <div class="small fw-medium">{{ $offre->auteur->nom_complet }}</div>
                </div>
                <hr>
                <dl class="small mb-0">
                    <dt class="text-muted fw-normal">Contrat</dt>
                    <dd class="fw-medium mb-2">{{ $offre->type_libelle }}</dd>
                    @if ($offre->localisation)
                        <dt class="text-muted fw-normal">Lieu</dt>
                        <dd class="fw-medium mb-2">{{ $offre->localisation }}</dd>
                    @endif
                    @if ($offre->salaire)
                        <dt class="text-muted fw-normal">Rémunération</dt>
                        <dd class="fw-medium mb-0">{{ $offre->salaire }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
@if ($errors->any())
    document.getElementById('postuler')?.scrollIntoView({ behavior: 'smooth' });
@endif
</script>
@endpush
@endsection
