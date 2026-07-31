@extends('layouts.admin')

@section('title', $entreprise->nom)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.entreprises.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold pt-3 border-bottom-0 d-flex align-items-center gap-3">
                @if ($entreprise->logo_url)
                    <img src="{{ $entreprise->logo_url }}" class="rounded" style="width:48px;height:48px;object-fit:cover;">
                @endif
                <span><i class="bi bi-building me-1 text-faaci-steel"></i> {{ $entreprise->nom }}</span>
            </div>
            <div class="card-body pt-0">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted fw-normal small">Propriétaire</dt>
                    <dd class="col-sm-8 fw-medium">{{ $entreprise->proprietaire?->nom_complet ?? '—' }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Secteur</dt>
                    <dd class="col-sm-8">{{ $entreprise->secteur ?: '—' }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Localisation</dt>
                    <dd class="col-sm-8">{{ $entreprise->localisation ?: '—' }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Fondée en</dt>
                    <dd class="col-sm-8">{{ $entreprise->annee_creation ?: '—' }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Téléphone</dt>
                    <dd class="col-sm-8">{{ $entreprise->telephone ?: '—' }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">E-mail</dt>
                    <dd class="col-sm-8">{{ $entreprise->email_contact ?: '—' }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Site web</dt>
                    <dd class="col-sm-8">
                        @if ($entreprise->site_web)
                            <a href="{{ $entreprise->site_web }}" target="_blank" rel="noopener">{{ $entreprise->site_web }}</a>
                        @else
                            —
                        @endif
                    </dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Statut</dt>
                    <dd class="col-sm-8">
                        @php $map = ['en_attente'=>'warning','actif'=>'success','rejete'=>'danger','inactif'=>'secondary']; @endphp
                        <span class="badge bg-{{ $map[$entreprise->statut] ?? 'secondary' }}">
                            {{ \App\Models\Entreprise::statutsLibelles()[$entreprise->statut] }}
                        </span>
                    </dd>

                    @if ($entreprise->motif_rejet)
                        <dt class="col-sm-4 text-muted fw-normal small">Motif rejet</dt>
                        <dd class="col-sm-8 text-danger">{{ $entreprise->motif_rejet }}</dd>
                    @endif

                    @if ($entreprise->description)
                        <dt class="col-sm-4 text-muted fw-normal small">Description</dt>
                        <dd class="col-sm-8">{{ $entreprise->description }}</dd>
                    @endif

                    <dt class="col-sm-4 text-muted fw-normal small">Soumise le</dt>
                    <dd class="col-sm-8">{{ $entreprise->created_at->translatedFormat('d F Y') }}</dd>

                    @if ($entreprise->validateur)
                        <dt class="col-sm-4 text-muted fw-normal small">Validée par</dt>
                        <dd class="col-sm-8">{{ $entreprise->validateur->nom_complet }} — {{ $entreprise->date_validation?->translatedFormat('d F Y') }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if ($entreprise->statut === 'en_attente')
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-check2-circle me-1 text-success"></i> Valider
                </div>
                <div class="card-body pt-0">
                    <p class="text-muted small">Publier cette entreprise dans l'annuaire Alumni.</p>
                    <form method="POST" action="{{ route('admin.entreprises.valider', $entreprise) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-check-lg me-1"></i> Valider l'entreprise
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-x-circle me-1 text-danger"></i> Rejeter
                </div>
                <div class="card-body pt-0">
                    <form method="POST" action="{{ route('admin.entreprises.rejeter', $entreprise) }}">
                        @csrf @method('PATCH')
                        <div class="mb-2">
                            <label class="form-label small">Motif <span class="text-danger">*</span></label>
                            <textarea name="motif_rejet" rows="3" class="form-control form-control-sm" required maxlength="500"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="bi bi-x-lg me-1"></i> Rejeter
                        </button>
                    </form>
                </div>
            </div>
        @elseif ($entreprise->statut === 'actif')
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.entreprises.desactiver', $entreprise) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-outline-warning btn-sm w-100">
                            <i class="bi bi-pause-circle me-1"></i> Désactiver
                        </button>
                    </form>
                </div>
            </div>
        @elseif ($entreprise->statut === 'inactif')
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.entreprises.reactiver', $entreprise) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-outline-success btn-sm w-100">
                            <i class="bi bi-play-circle me-1"></i> Réactiver
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
