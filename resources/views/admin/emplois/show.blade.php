@extends('layouts.admin')

@section('title', $offre->titre)
@section('page-title', 'Offre d\'emploi')

@section('content')
<div class="mb-3 d-flex flex-wrap gap-2">
    <a href="{{ route('admin.emplois.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
    <a href="{{ route('admin.emplois.candidatures', $offre) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-people me-1"></i> Candidatures ({{ $offre->candidatures->count() }})
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-2 mb-2">
                    <h4 class="fw-bold flex-grow-1 mb-0">{{ $offre->titre }}</h4>
                    @php
                        $colors = ['cdi'=>'success','cdd'=>'primary','stage'=>'info','freelance'=>'warning','alternance'=>'secondary'];
                    @endphp
                    <span class="badge bg-{{ $colors[$offre->type_contrat] ?? 'secondary' }}">{{ $offre->type_libelle }}</span>
                </div>
                <div class="d-flex flex-wrap gap-3 small text-muted mb-4">
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
                <div class="rich-content">{!! $offre->description !!}</div>

                @if ($offre->competences_requises)
                    <hr>
                    <h6 class="fw-semibold mb-2">Compétences requises</h6>
                    <div class="rich-content text-muted small">{!! $offre->competences_requises !!}</div>
                @endif

                @if ($offre->lien_externe)
                    <hr>
                    <a href="{{ $offre->lien_externe }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Lien de candidature externe
                    </a>
                @endif

                @if ($offre->motif_rejet)
                    <div class="alert alert-danger mt-3 mb-0">
                        <strong>Motif de rejet :</strong> {{ $offre->motif_rejet }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4 text-center">
                @php
                    $sc = ['en_attente'=>'warning','active'=>'success','brouillon'=>'secondary','expiree'=>'dark','rejetee'=>'danger'];
                @endphp
                <span class="badge bg-{{ $sc[$offre->statut] ?? 'secondary' }} fs-6 px-3 py-2">{{ $offre->statut_libelle }}</span>
                @if ($offre->validateur)
                    <div class="text-muted small mt-2">Validé par {{ $offre->validateur->nom_complet }}</div>
                @endif
            </div>
        </div>

        @if ($offre->statut === 'en_attente')
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">Actions</div>
                <div class="card-body pt-0 d-grid gap-2">
                    <form method="POST" action="{{ route('admin.emplois.valider', $offre) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-lg me-1"></i> Valider et publier
                        </button>
                    </form>
                    <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#modalRejeter">
                        <i class="bi bi-x-lg me-1"></i> Rejeter
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="modalRejeter" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Rejeter l'offre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.emplois.rejeter', $offre) }}">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <label class="form-label">Motif <span class="text-danger">*</span></label>
                    <textarea name="motif_rejet" rows="3" class="form-control" required
                              placeholder="Expliquez la raison du rejet…"></textarea>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
