@extends('layouts.admin')

@section('title', 'Don #' . $don->id)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.dons.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                <i class="bi bi-gift me-1 text-faaci-steel"></i> Détail du don
            </div>
            <div class="card-body pt-0">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted fw-normal small">Donateur</dt>
                    <dd class="col-sm-8 fw-medium">{{ $don->donateur?->nom_complet ?? '—' }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Nature</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-light text-dark border">
                            {{ \App\Models\Don::natures()[$don->nature] ?? $don->nature }}
                        </span>
                    </dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Libellé</dt>
                    <dd class="col-sm-8 fw-medium">{{ $don->libelle }}</dd>

                    @if ($don->nature === 'argent')
                        <dt class="col-sm-4 text-muted fw-normal small">Montant</dt>
                        <dd class="col-sm-8 fw-medium">{{ $don->montant ? number_format($don->montant, 0, ',', ' ').' FCFA' : '—' }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal small">Moyen de paiement</dt>
                        <dd class="col-sm-8">{{ $don->moyen_paiement ? \App\Models\Don::moyensPaiement()[$don->moyen_paiement] : '—' }}</dd>
                    @else
                        <dt class="col-sm-4 text-muted fw-normal small">Valeur estimée</dt>
                        <dd class="col-sm-8">{{ $don->valeur_estimee ?: '—' }}</dd>
                    @endif

                    @if ($don->description)
                        <dt class="col-sm-4 text-muted fw-normal small">Description</dt>
                        <dd class="col-sm-8">{{ $don->description }}</dd>
                    @endif

                    <dt class="col-sm-4 text-muted fw-normal small">Statut</dt>
                    <dd class="col-sm-8">
                        @php
                            $map = ['en_attente'=>'warning','confirme'=>'success','rejete'=>'danger','annule'=>'secondary'];
                        @endphp
                        <span class="badge bg-{{ $map[$don->statut] ?? 'secondary' }}">
                            {{ \App\Models\Don::statutsLibelles()[$don->statut] }}
                        </span>
                    </dd>

                    @if ($don->motif_rejet)
                        <dt class="col-sm-4 text-muted fw-normal small">Motif rejet</dt>
                        <dd class="col-sm-8 text-danger">{{ $don->motif_rejet }}</dd>
                    @endif

                    <dt class="col-sm-4 text-muted fw-normal small">Soumis le</dt>
                    <dd class="col-sm-8">{{ $don->created_at->translatedFormat('d F Y à H:i') }}</dd>

                    @if ($don->validateur)
                        <dt class="col-sm-4 text-muted fw-normal small">Traité par</dt>
                        <dd class="col-sm-8">{{ $don->validateur->nom_complet }} — {{ $don->date_validation?->translatedFormat('d F Y') }}</dd>
                    @endif
                </dl>
            </div>
        </div>

        @if ($don->getFirstMedia('preuves'))
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-paperclip me-1 text-faaci-steel"></i> Justificatif
                </div>
                <div class="card-body pt-0">
                    @php $media = $don->getFirstMedia('preuves'); @endphp
                    @if (in_array($media->mime_type, ['image/jpeg','image/png','image/webp','image/gif']))
                        <img src="{{ $media->getUrl() }}" class="img-fluid rounded" style="max-height:400px;">
                    @else
                        <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-download me-1"></i> Télécharger le justificatif
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        @if ($don->statut === 'en_attente')
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-check2-circle me-1 text-success"></i> Confirmer
                </div>
                <div class="card-body pt-0">
                    <p class="text-muted small">Confirmer ce don pour l'enregistrer dans les comptes de la fondation.</p>
                    <form method="POST" action="{{ route('admin.dons.confirmer', $don) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-check-lg me-1"></i> Confirmer le don
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-x-circle me-1 text-danger"></i> Rejeter
                </div>
                <div class="card-body pt-0">
                    <form method="POST" action="{{ route('admin.dons.rejeter', $don) }}">
                        @csrf @method('PATCH')
                        <div class="mb-2">
                            <label class="form-label small">Motif de rejet <span class="text-danger">*</span></label>
                            <textarea name="motif_rejet" rows="3" class="form-control form-control-sm" required maxlength="500"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="bi bi-x-lg me-1"></i> Rejeter
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
