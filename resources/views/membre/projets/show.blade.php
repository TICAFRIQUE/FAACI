@extends('layouts.app')

@section('title', $projet->titre)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.projets.index') }}">Projets</a></li>
    <li class="breadcrumb-item active text-truncate" style="max-width:200px;">{{ $projet->titre }}</li>
@endsection

@section('content')
<div class="mb-3">
    <a href="{{ route('membre.projets.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row g-4">
    {{-- Contenu principal --}}
    <div class="col-lg-8">
        @if ($projet->image_url)
            <img src="{{ $projet->image_url }}" alt="{{ $projet->titre }}"
                 class="img-fluid rounded-3 mb-4 w-100" style="max-height:380px;object-fit:cover;">
        @endif

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-2 mb-3">
                    <h4 class="fw-bold text-dark flex-grow-1 mb-0">{{ $projet->titre }}</h4>
                    @php
                        $badges = [
                            'brouillon'      => ['secondary','Brouillon'],
                            'en_attente'     => ['warning','En attente'],
                            'valide'         => ['info','Validé'],
                            'en_financement' => ['primary','En financement'],
                            'finance'        => ['success','Financé'],
                            'en_cours'       => ['success','En cours'],
                            'termine'        => ['dark','Terminé'],
                            'rejete'         => ['danger','Rejeté'],
                        ];
                        [$bc, $bl] = $badges[$projet->statut] ?? ['secondary','—'];
                    @endphp
                    <span class="badge bg-{{ $bc }} flex-shrink-0">{{ $bl }}</span>
                </div>

                <div class="d-flex align-items-center gap-2 mb-4 small text-muted">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:26px;height:26px;background:var(--faaci-navy);color:#fff;font-size:0.7rem;font-weight:700;">
                        {{ mb_strtoupper(mb_substr($projet->porteur->prenom,0,1)) }}{{ mb_strtoupper(mb_substr($projet->porteur->nom,0,1)) }}
                    </div>
                    <span>{{ $projet->porteur->nom_complet }}</span>
                    <span>·</span>
                    <span>{{ $projet->created_at->translatedFormat('d F Y') }}</span>
                </div>

                <div class="rich-content">{!! $projet->description !!}</div>

                @if ($projet->motif_rejet)
                    <div class="alert alert-danger mt-4 mb-0">
                        <strong><i class="bi bi-x-circle me-1"></i>Motif de rejet :</strong> {{ $projet->motif_rejet }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Actions porteur --}}
        @if ($projet->utilisateur_id === auth()->id())
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3 d-flex flex-wrap gap-2">
                    @if ($projet->statut === 'brouillon')
                        <a href="{{ route('membre.projets.edit', $projet) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil me-1"></i> Modifier
                        </a>
                        <form method="POST" action="{{ route('membre.projets.soumettre', $projet) }}">
                            @csrf
                            <button type="submit" class="btn btn-faaci-primary btn-sm">
                                <i class="bi bi-send me-1"></i> Soumettre à la validation
                            </button>
                        </form>
                        <form method="POST" action="{{ route('membre.projets.destroy', $projet) }}"
                              data-confirm="Supprimer ce projet ?">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash me-1"></i> Supprimer
                            </button>
                        </form>
                    @else
                        <span class="text-muted small align-self-center">
                            <i class="bi bi-info-circle me-1"></i>
                            Le projet ne peut plus être modifié (statut : {{ \App\Models\Projet::statutsLibelles()[$projet->statut] }}).
                        </span>
                    @endif
                </div>
            </div>
        @endif

        {{-- Documents joints --}}
        @php $documents = $projet->getMedia('documents'); @endphp
        @if ($documents->isNotEmpty())
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-paperclip me-1 text-faaci-steel"></i> Documents
                </div>
                <div class="card-body pt-0">
                    @foreach ($documents as $doc)
                        <a href="{{ $doc->getUrl() }}" target="_blank"
                           class="d-flex align-items-center gap-2 py-2 text-decoration-none text-dark border-bottom">
                            <i class="bi bi-file-earmark-{{ Str::endsWith($doc->file_name, '.pdf') ? 'pdf text-danger' : 'word text-primary' }} fs-5"></i>
                            <span class="small flex-grow-1">{{ $doc->file_name }}</span>
                            <span class="text-muted small">{{ round($doc->size / 1024) }} Ko</span>
                            <i class="bi bi-download text-muted small"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Contributeurs --}}
        @if ($projet->contributions->count())
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-people me-1 text-faaci-steel"></i>
                    Contributeurs ({{ $projet->contributions->count() }})
                </div>
                <div class="card-body pt-0">
                    @foreach ($projet->contributions->whereNotIn('statut', ['cancelled']) as $c)
                        <div class="d-flex align-items-center gap-2 py-2 border-bottom">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:30px;height:30px;background:var(--faaci-navy);color:#fff;font-size:0.72rem;font-weight:700;">
                                {{ mb_strtoupper(mb_substr($c->contributeur->prenom,0,1)) }}{{ mb_strtoupper(mb_substr($c->contributeur->nom,0,1)) }}
                            </div>
                            <span class="small fw-medium flex-grow-1">{{ $c->contributeur->nom_complet }}</span>
                            <span class="small text-muted">{{ number_format($c->montant_promis, 0, ',', ' ') }} FCFA</span>
                            @php
                                $cs = ['pending'=>'warning','confirmed'=>'info','paid'=>'success','partial'=>'primary','cancelled'=>'secondary'];
                                $cl = \App\Models\Contribution::statutsLibelles()[$c->statut] ?? $c->statut;
                            @endphp
                            <span class="badge bg-{{ $cs[$c->statut] ?? 'secondary' }} small">{{ $cl }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
        {{-- Jauge de financement --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                @if ($projet->montant_cible)
                    <div class="text-center mb-3">
                        <div class="fs-3 fw-bold text-faaci-navy">{{ number_format($projet->montant_collecte, 0, ',', ' ') }} FCFA</div>
                        <div class="text-muted small">collectés sur {{ number_format($projet->montant_cible, 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="progress mb-3" style="height:8px;">
                        <div class="progress-bar"
                             style="width:{{ $projet->pourcentage }}%;background:var(--faaci-steel);"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span><strong class="text-dark">{{ $projet->pourcentage }} %</strong> atteint</span>
                        <span><strong class="text-dark">{{ $projet->contributions->whereNotIn('statut',['cancelled'])->count() }}</strong> contributeur(s)</span>
                    </div>
                @else
                    <div class="text-center mb-3">
                        <div class="fs-3 fw-bold text-faaci-navy">{{ number_format($projet->montant_collecte, 0, ',', ' ') }} FCFA</div>
                        <div class="text-muted small">collectés (financement ouvert)</div>
                    </div>
                @endif

                @if ($projet->date_fin_financement)
                    <hr>
                    <div class="text-center small text-muted">
                        <i class="bi bi-clock me-1"></i> Fin le {{ $projet->date_fin_financement->translatedFormat('d F Y') }}
                        <br><span class="fw-medium">{{ $projet->date_fin_financement->diffForHumans() }}</span>
                    </div>
                @endif

                {{-- Bouton financer --}}
                @if ($projet->statut === 'en_financement' && $projet->utilisateur_id !== auth()->id())
                    @if ($maContribution && $maContribution->statut !== 'cancelled')
                        <div class="alert alert-success mt-3 mb-0 small text-center">
                            <i class="bi bi-check-circle me-1"></i>
                            Vous avez promis {{ number_format($maContribution->montant_promis, 0, ',', ' ') }} FCFA
                            <br>
                            <span class="badge bg-{{ ['pending'=>'warning','confirmed'=>'info','paid'=>'success','partial'=>'primary'][$maContribution->statut] ?? 'secondary' }} mt-1">
                                {{ \App\Models\Contribution::statutsLibelles()[$maContribution->statut] }}
                            </span>
                            @if (in_array($maContribution->statut, ['pending','partial']))
                                <a href="{{ route('membre.contributions.paiement', $maContribution) }}"
                                   class="btn btn-outline-success btn-sm d-block mt-2">
                                    <i class="bi bi-cash me-1"></i> Déclarer mon paiement
                                </a>
                            @endif
                        </div>
                    @else
                        <button type="button" class="btn btn-faaci-primary w-100 mt-3"
                                data-bs-toggle="modal" data-bs-target="#modalFinancer">
                            <i class="bi bi-hand-thumbs-up me-1"></i> Financer ce projet
                        </button>
                    @endif
                @endif
            </div>
        </div>

        {{-- Infos porteur --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <p class="small text-muted mb-2 fw-semibold">Porteur du projet</p>
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:36px;height:36px;background:var(--faaci-navy);color:#fff;font-size:0.8rem;font-weight:700;">
                        {{ mb_strtoupper(mb_substr($projet->porteur->prenom,0,1)) }}{{ mb_strtoupper(mb_substr($projet->porteur->nom,0,1)) }}
                    </div>
                    <div>
                        <div class="fw-medium small">{{ $projet->porteur->nom_complet }}</div>
                        @if ($projet->porteur->secteur)
                            <div class="text-muted" style="font-size:0.75rem;">{{ $projet->porteur->secteur }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal financement --}}
@if ($projet->statut === 'en_financement' && $projet->utilisateur_id !== auth()->id())
<div class="modal fade" id="modalFinancer" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Financer ce projet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('membre.contributions.store', $projet) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info small mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Le paiement se fait <strong>hors plateforme</strong> (cash, Orange Money, Wave, virement).
                        Vous déclarerez ensuite votre paiement depuis "Mes contributions".
                    </div>
                    <div class="mb-3">
                        <label for="montant_promis" class="form-label">Montant promis (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" id="montant_promis" name="montant_promis"
                               class="form-control @error('montant_promis') is-invalid @enderror"
                               min="1" placeholder="Ex : 50000" value="{{ old('montant_promis') }}" required>
                        @error('montant_promis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-0">
                        <label for="note" class="form-label">Note (optionnel)</label>
                        <textarea id="note" name="note" rows="2"
                                  class="form-control"
                                  placeholder="Un message pour le porteur…">{{ old('note') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-faaci-primary">
                        <i class="bi bi-check-lg me-1"></i> Confirmer ma promesse
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
@if ($errors->has('montant_promis') || $errors->has('note'))
document.addEventListener('DOMContentLoaded', function () {
    new bootstrap.Modal(document.getElementById('modalFinancer')).show();
});
@endif
</script>
@endpush
@endsection
