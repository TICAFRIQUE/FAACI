@extends('layouts.app')

@section('title', 'Déclarer un paiement')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.contributions.index') }}">Mes contributions</a></li>
    <li class="breadcrumb-item active">Déclarer un paiement</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-bold mb-0">Déclarer un paiement</h4>
            <a href="{{ route('membre.contributions.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>

        {{-- Résumé de la contribution --}}
        @php
            $resteAPayer = max(0, $contribution->montant_promis - $contribution->montant_paye);
        @endphp
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="small text-muted fw-semibold mb-2 text-uppercase" style="letter-spacing:.05em;">
                    {{ $contribution->projet->titre }}
                </div>
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="fs-5 fw-bold text-faaci-navy">
                            {{ number_format($contribution->montant_promis, 0, ',', ' ') }}
                        </div>
                        <div class="small text-muted">FCFA promis</div>
                    </div>
                    <div class="col-4">
                        <div class="fs-5 fw-bold text-success">
                            {{ number_format($contribution->montant_paye, 0, ',', ' ') }}
                        </div>
                        <div class="small text-muted">FCFA déclarés</div>
                    </div>
                    <div class="col-4">
                        <div class="fs-5 fw-bold {{ $resteAPayer > 0 ? 'text-warning' : 'text-success' }}">
                            {{ number_format($resteAPayer, 0, ',', ' ') }}
                        </div>
                        <div class="small text-muted">FCFA restants</div>
                    </div>
                </div>
                @if ($contribution->montant_promis > 0)
                    <div class="progress mt-3" style="height:6px;">
                        @php $pct = min(100, round($contribution->montant_paye / $contribution->montant_promis * 100)); @endphp
                        <div class="progress-bar bg-success" style="width:{{ $pct }}%;"></div>
                    </div>
                    <div class="text-end small text-muted mt-1">{{ $pct }} % payé</div>
                @endif
            </div>
        </div>

        {{-- Historique des déclarations précédentes --}}
        @if ($contribution->declarations->isNotEmpty())
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold pt-3 pb-2 border-bottom">
                    <i class="bi bi-clock-history me-1 text-faaci-steel"></i>
                    Historique de vos déclarations ({{ $contribution->declarations->count() }})
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 align-middle table-mobile-cards">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Date</th>
                                    <th>Montant</th>
                                    <th>Moyen</th>
                                    <th>Note</th>
                                    <th>Preuve</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contribution->declarations as $d)
                                    <tr>
                                        <td class="ps-3 small text-muted" data-label="Date">
                                            {{ $d->created_at->translatedFormat('d M Y') }}
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
                                                    <i class="bi bi-paperclip"></i>
                                                </a>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td data-label="Statut">
                                            @if ($d->valide_par)
                                                <span class="badge bg-success small">Validé</span>
                                            @else
                                                <span class="badge bg-warning text-dark small">En attente</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Formulaire nouvelle déclaration --}}
        @if ($resteAPayer > 0 || $contribution->statut === \App\Models\Contribution::STATUT_PENDING)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold pt-3 pb-2 border-bottom">
                    <i class="bi bi-plus-circle me-1 text-faaci-steel"></i>
                    Nouvelle déclaration de paiement
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info small mb-4">
                        <i class="bi bi-info-circle me-1"></i>
                        Le paiement se fait <strong>hors plateforme</strong> (cash, Orange Money, Wave…).
                        Déclarez ici le montant que vous venez de payer. Chaque déclaration s'ajoute au total.
                    </div>

                    <form method="POST" action="{{ route('membre.contributions.declarer-paiement', $contribution) }}"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="montant_paye" class="form-label">
                                Montant payé cette fois (FCFA) <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="montant_paye" name="montant_paye"
                                   class="form-control @error('montant_paye') is-invalid @enderror"
                                   value="{{ old('montant_paye', $resteAPayer ?: '') }}"
                                   min="1" required>
                            @if ($resteAPayer > 0)
                                <div class="form-text">
                                    Reste à déclarer : <strong>{{ number_format($resteAPayer, 0, ',', ' ') }} FCFA</strong>
                                </div>
                            @endif
                            @error('montant_paye')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="moyen_paiement" class="form-label">
                                Moyen de paiement <span class="text-danger">*</span>
                            </label>
                            <select id="moyen_paiement" name="moyen_paiement"
                                    class="form-select @error('moyen_paiement') is-invalid @enderror" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach (\App\Models\Contribution::moyensPaiement() as $val => $lib)
                                    <option value="{{ $val }}" {{ old('moyen_paiement') === $val ? 'selected' : '' }}>
                                        {{ $lib }}
                                    </option>
                                @endforeach
                            </select>
                            @error('moyen_paiement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="preuve" class="form-label">Pièce justificative (optionnel)</label>
                            <input type="file" id="preuve" name="preuve"
                                   class="form-control @error('preuve') is-invalid @enderror"
                                   accept="image/*,.pdf">
                            <div class="form-text">Capture Orange Money, reçu de virement… Max 4 Mo.</div>
                            @error('preuve')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="note" class="form-label">Note (optionnel)</label>
                            <textarea id="note" name="note" rows="2" class="form-control"
                                      placeholder="Informations pour l'administrateur…">{{ old('note') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-faaci-primary w-100">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer cette déclaration
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-1"></i>
                Vous avez déclaré la totalité de votre promesse
                ({{ number_format($contribution->montant_promis, 0, ',', ' ') }} FCFA).
                En attente de validation admin.
            </div>
        @endif

    </div>
</div>
@endsection
