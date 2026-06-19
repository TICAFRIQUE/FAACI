@extends('layouts.admin')

@section('title', 'Contribution #' . $contribution->id)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.contributions.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                <i class="bi bi-cash-coin me-1 text-faaci-steel"></i> Détail de la contribution
            </div>
            <div class="card-body pt-0">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted fw-normal small">Contributeur</dt>
                    <dd class="col-sm-8 fw-medium">{{ $contribution->contributeur->nom_complet }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Projet</dt>
                    <dd class="col-sm-8 fw-medium">
                        <a href="{{ route('admin.projets.show', $contribution->projet) }}">
                            {{ $contribution->projet->titre }}
                        </a>
                    </dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Montant promis</dt>
                    <dd class="col-sm-8 fw-medium">{{ number_format($contribution->montant_promis, 0, ',', ' ') }} FCFA</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Montant déclaré payé</dt>
                    <dd class="col-sm-8 fw-medium">
                        {{ $contribution->montant_paye > 0 ? number_format($contribution->montant_paye, 0, ',', ' ').' FCFA' : '—' }}
                    </dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Moyen de paiement</dt>
                    <dd class="col-sm-8">
                        {{ $contribution->moyen_paiement ? \App\Models\Contribution::moyensPaiement()[$contribution->moyen_paiement] : '—' }}
                    </dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Date déclaration</dt>
                    <dd class="col-sm-8">
                        {{ $contribution->date_declaration_paiement?->translatedFormat('d F Y à H:i') ?? '—' }}
                    </dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Note du membre</dt>
                    <dd class="col-sm-8">{{ $contribution->note ?: '—' }}</dd>

                    @if ($contribution->motif_rejet)
                        <dt class="col-sm-4 text-muted fw-normal small">Motif rejet</dt>
                        <dd class="col-sm-8 text-danger">{{ $contribution->motif_rejet }}</dd>
                    @endif

                    @if ($contribution->validateur)
                        <dt class="col-sm-4 text-muted fw-normal small">Validé par</dt>
                        <dd class="col-sm-8">{{ $contribution->validateur->nom_complet }} le {{ $contribution->date_validation?->translatedFormat('d F Y') }}</dd>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Historique des déclarations --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold pt-3 pb-2 border-bottom">
                <i class="bi bi-clock-history me-1 text-faaci-steel"></i>
                Historique des déclarations ({{ $contribution->declarations->count() }})
            </div>
            @if ($contribution->declarations->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 align-middle table-mobile-cards">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Montant</th>
                                <th>Moyen</th>
                                <th>Note</th>
                                <th>Preuve</th>
                                <th>Validation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contribution->declarations as $d)
                                <tr>
                                    <td class="ps-3 small text-muted" data-label="Date">
                                        {{ $d->created_at->translatedFormat('d M Y à H:i') }}
                                    </td>
                                    <td class="fw-medium small" data-label="Montant">
                                        {{ number_format($d->montant, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="small text-muted" data-label="Moyen">
                                        {{ \App\Models\Contribution::moyensPaiement()[$d->moyen_paiement] ?? $d->moyen_paiement }}
                                    </td>
                                    <td class="small text-muted" data-label="Note">{{ $d->note ?: '—' }}</td>
                                    <td data-label="Preuve">
                                        @if ($d->preuve_url)
                                            <a href="{{ $d->preuve_url }}" target="_blank"
                                               class="btn btn-sm btn-outline-secondary py-0">
                                                <i class="bi bi-paperclip"></i> Voir
                                            </a>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td data-label="Statut">
                                        @if ($d->valide_par)
                                            <span class="badge bg-success small">
                                                Validé le {{ $d->date_validation?->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark small">En attente</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td class="ps-3 fw-semibold small" colspan="1">Total</td>
                                <td class="fw-bold small text-faaci-navy">
                                    {{ number_format($contribution->declarations->sum('montant'), 0, ',', ' ') }} FCFA
                                </td>
                                <td colspan="4" class="small text-muted">
                                    sur {{ number_format($contribution->montant_promis, 0, ',', ' ') }} FCFA promis
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="card-body pt-0">
                    <p class="text-muted small mb-0">Aucune déclaration de paiement pour l'instant.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body text-center p-4">
                @php
                    $badges = ['pending'=>['warning','En attente'],'confirmed'=>['info','Déclaré'],'paid'=>['success','Validé'],'partial'=>['primary','Partiel'],'cancelled'=>['secondary','Annulé']];
                    [$bc,$bl] = $badges[$contribution->statut] ?? ['secondary','—'];
                @endphp
                <span class="badge bg-{{ $bc }} fs-6 px-3 py-2">{{ $bl }}</span>
            </div>
        </div>

        @if (in_array($contribution->statut, ['confirmed', 'partial']))
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">Actions</div>
                <div class="card-body pt-0 d-grid gap-2">
                    <form method="POST" action="{{ route('admin.contributions.valider', $contribution) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-lg me-1"></i> Valider le paiement
                        </button>
                    </form>
                    <button type="button" class="btn btn-outline-danger w-100"
                            data-bs-toggle="modal" data-bs-target="#modalRejeter">
                        <i class="bi bi-x-lg me-1"></i> Rejeter la déclaration
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Modal rejet --}}
<div class="modal fade" id="modalRejeter" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Rejeter la déclaration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.contributions.rejeter', $contribution) }}">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-info small">Le membre devra re-déclarer son paiement.</div>
                    <label for="motif_rejet" class="form-label">Motif <span class="text-danger">*</span></label>
                    <textarea id="motif_rejet" name="motif_rejet" rows="3"
                              class="form-control" required
                              placeholder="Ex : Montant incorrect, capture illisible…"></textarea>
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
