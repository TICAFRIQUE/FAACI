@extends('layouts.admin')
@section('title', 'Investissement #' . $contribution->id)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.contributions.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Détail --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                <i class="bi bi-cash-coin me-1 text-faaci-steel"></i> Détail de l'investissement
            </div>
            <div class="card-body pt-0">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted fw-normal small">Membre</dt>
                    <dd class="col-sm-8 fw-medium">{{ $contribution->contributeur?->nom_complet ?? '—' }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Projet</dt>
                    <dd class="col-sm-8 fw-medium">
                        <a href="{{ route('admin.projets.show', $contribution->projet) }}">
                            {{ $contribution->projet?->titre ?? '—' }}
                        </a>
                    </dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Montant promis</dt>
                    <dd class="col-sm-8 fw-medium">{{ number_format($contribution->montant_promis, 0, ',', ' ') }} FCFA</dd>

                    <dt class="col-sm-4 text-muted fw-normal small">Montant payé</dt>
                    <dd class="col-sm-8 fw-medium">
                        {{ $contribution->montant_paye > 0 ? number_format($contribution->montant_paye, 0, ',', ' ').' FCFA' : '—' }}
                    </dd>

                    @if ($contribution->moyen_paiement)
                        <dt class="col-sm-4 text-muted fw-normal small">Dernier moyen</dt>
                        <dd class="col-sm-8">{{ \App\Models\Contribution::moyensPaiement()[$contribution->moyen_paiement] }}</dd>
                    @endif

                    @if ($contribution->note)
                        <dt class="col-sm-4 text-muted fw-normal small">Note</dt>
                        <dd class="col-sm-8">{{ $contribution->note }}</dd>
                    @endif

                    <dt class="col-sm-4 text-muted fw-normal small">Date promesse</dt>
                    <dd class="col-sm-8 text-muted small">{{ $contribution->created_at->translatedFormat('d F Y') }}</dd>
                </dl>
            </div>
        </div>

        {{-- Historique des paiements --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold pt-3 pb-2 border-bottom">
                <i class="bi bi-clock-history me-1 text-faaci-steel"></i>
                Historique des paiements ({{ $contribution->declarations->count() }})
            </div>
            @if ($contribution->declarations->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Montant</th>
                                <th>Moyen</th>
                                <th>Note</th>
                                <th>Preuve</th>
                                <th>Enregistré par</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contribution->declarations as $d)
                                <tr>
                                    <td class="ps-3 small text-muted">{{ $d->created_at->format('d/m/Y à H:i') }}</td>
                                    <td class="fw-medium small">{{ number_format($d->montant, 0, ',', ' ') }} FCFA</td>
                                    <td class="small text-muted">{{ \App\Models\Contribution::moyensPaiement()[$d->moyen_paiement] ?? $d->moyen_paiement }}</td>
                                    <td class="small text-muted">{{ $d->note ?: '—' }}</td>
                                    <td>
                                        @if ($d->preuve_url)
                                            <a href="{{ $d->preuve_url }}" target="_blank" class="btn btn-sm btn-outline-secondary py-0">
                                                <i class="bi bi-paperclip"></i>
                                            </a>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">{{ $d->validateur?->nom_complet ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td class="ps-3 fw-semibold small">Total</td>
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
                    <p class="text-muted small mb-0">Aucun paiement enregistré pour l'instant.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Badge statut --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body text-center p-4">
                @php
                    $col = \App\Models\Contribution::statutsBadge()[$contribution->statut] ?? 'secondary';
                    $lib = \App\Models\Contribution::statutsLibelles()[$contribution->statut] ?? '—';
                @endphp
                <span class="badge bg-{{ $col }} fs-6 px-3 py-2">{{ $lib }}</span>

                @if ($contribution->statut === \App\Models\Contribution::STATUT_PARTIEL)
                    @php $pct = $contribution->montant_promis > 0 ? min(100, round($contribution->montant_paye / $contribution->montant_promis * 100)) : 0; @endphp
                    <div class="progress mt-3 mb-1" style="height:8px;">
                        <div class="progress-bar bg-primary" style="width:{{ $pct }}%"></div>
                    </div>
                    <div class="text-muted small">{{ number_format($contribution->montant_paye,0,',',' ') }} / {{ number_format($contribution->montant_promis,0,',',' ') }} FCFA ({{ $pct }}%)</div>
                @endif
            </div>
        </div>

        {{-- Formulaire enregistrement paiement --}}
        @if (in_array($contribution->statut, [\App\Models\Contribution::STATUT_PROMESSE, \App\Models\Contribution::STATUT_PARTIEL]))
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-cash-coin me-1 text-faaci-steel"></i> Enregistrer un paiement
                </div>
                <div class="card-body pt-1">
                    @if (session('error'))
                        <div class="alert alert-danger small py-2">{{ session('error') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger small py-2">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST"
                          action="{{ route('admin.contributions.paiement', $contribution) }}"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Montant payé (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" name="montant" min="1" step="1"
                                   value="{{ old('montant') }}"
                                   class="form-control form-control-sm @error('montant') is-invalid @enderror"
                                   placeholder="Ex : 25000" required>
                            @error('montant')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text small">
                                Reste à payer : {{ number_format(max(0, $contribution->montant_promis - $contribution->montant_paye), 0, ',', ' ') }} FCFA
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Moyen de paiement <span class="text-danger">*</span></label>
                            <select name="moyen_paiement" class="form-select form-select-sm @error('moyen_paiement') is-invalid @enderror" required>
                                <option value="">— Sélectionner —</option>
                                @foreach (\App\Models\Contribution::moyensPaiement() as $val => $lib)
                                    <option value="{{ $val }}" {{ old('moyen_paiement') === $val ? 'selected' : '' }}>{{ $lib }}</option>
                                @endforeach
                            </select>
                            @error('moyen_paiement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Note (optionnel)</label>
                            <textarea name="note" rows="2" class="form-control form-control-sm"
                                      placeholder="Ex : Reçu Orange Money #12345…">{{ old('note') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Preuve (optionnel)</label>
                            <input type="file" name="preuve" accept=".jpg,.jpeg,.png,.webp,.pdf"
                                   class="form-control form-control-sm @error('preuve') is-invalid @enderror">
                            <div class="form-text small">JPG, PNG, PDF · max 4 Mo</div>
                            @error('preuve')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-faaci-navy w-100 btn-sm">
                            <i class="bi bi-check-circle me-1"></i> Enregistrer le paiement
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
