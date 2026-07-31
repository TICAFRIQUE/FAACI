@extends('layouts.admin')
@section('title', 'Paiement cotisation #' . $paiement->id)
@section('content')
<div class="mb-3"><a href="{{ route('admin.cotisations.paiements.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Retour</a></div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold pt-3 border-bottom-0"><i class="bi bi-cash me-1 text-faaci-steel"></i> Détail du paiement</div>
            <div class="card-body pt-0">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted fw-normal small">Membre</dt>
                    <dd class="col-sm-8 fw-medium">{{ $paiement->membre?->nom_complet }}</dd>
                    <dt class="col-sm-4 text-muted fw-normal small">Type</dt>
                    <dd class="col-sm-8">{{ $paiement->type?->nom }}</dd>
                    <dt class="col-sm-4 text-muted fw-normal small">Montant déclaré</dt>
                    <dd class="col-sm-8 fw-bold">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</dd>
                    <dt class="col-sm-4 text-muted fw-normal small">Moyen</dt>
                    <dd class="col-sm-8">{{ $paiement->moyen_paiement ? \App\Models\PaiementCotisation::moyensPaiement()[$paiement->moyen_paiement] : '—' }}</dd>
                    <dt class="col-sm-4 text-muted fw-normal small">Note</dt>
                    <dd class="col-sm-8">{{ $paiement->note ?: '—' }}</dd>
                    <dt class="col-sm-4 text-muted fw-normal small">Saisi par</dt>
                    <dd class="col-sm-8">{{ $paiement->saiseur ? $paiement->saiseur->nom_complet.' (admin)' : 'Le membre' }}</dd>
                    <dt class="col-sm-4 text-muted fw-normal small">Statut</dt>
                    <dd class="col-sm-8">
                        @php $sc=['en_attente'=>'warning','valide'=>'success','rejete'=>'danger']; @endphp
                        <span class="badge bg-{{ $sc[$paiement->statut]??'secondary' }}">{{ ['en_attente'=>'En attente','valide'=>'Validé','rejete'=>'Rejeté'][$paiement->statut]??$paiement->statut }}</span>
                    </dd>
                    @if ($paiement->validateur)
                        <dt class="col-sm-4 text-muted fw-normal small">Validé par</dt>
                        <dd class="col-sm-8">{{ $paiement->validateur->nom_complet }} — {{ $paiement->date_validation?->translatedFormat('d F Y') }}</dd>
                    @endif
                    @if ($paiement->motif_rejet)
                        <dt class="col-sm-4 text-muted fw-normal small">Motif rejet</dt>
                        <dd class="col-sm-8 text-danger">{{ $paiement->motif_rejet }}</dd>
                    @endif
                </dl>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold pt-3 border-bottom-0"><i class="bi bi-calendar3 me-1 text-faaci-steel"></i> Périodes couvertes</div>
            <div class="card-body pt-0">
                @if ($paiement->periodes->isEmpty())
                    <p class="text-muted small mb-0">Aucune période liée.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light"><tr><th>Période</th><th>Montant attribué</th></tr></thead>
                            <tbody>
                                @foreach ($paiement->periodes as $periode)
                                    <tr>
                                        <td>{{ $periode->libelle }}</td>
                                        <td>{{ number_format($periode->pivot->montant_attribue, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if ($paiement->statut === 'en_attente')
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.cotisations.paiements.valider', $paiement) }}"
                          data-confirm="Valider ce paiement de {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA ?">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-lg me-1"></i> Valider</button>
                    </form>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.cotisations.paiements.rejeter', $paiement) }}">
                        @csrf @method('PATCH')
                        <textarea name="motif_rejet" rows="3" class="form-control form-control-sm mb-2" required placeholder="Motif de rejet…"></textarea>
                        <button type="submit" class="btn btn-outline-danger w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Rejeter</button>
                    </form>
                </div>
            </div>
        @endif
        @if ($paiement->getFirstMedia('preuves'))
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">Justificatif</div>
                <div class="card-body pt-0">
                    @php $media = $paiement->getFirstMedia('preuves'); @endphp
                    @if (str_starts_with($media->mime_type, 'image/'))
                        <img src="{{ $media->getUrl() }}" class="img-fluid rounded">
                    @else
                        <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-download me-1"></i> Télécharger</a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
