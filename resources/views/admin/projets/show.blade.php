@extends('layouts.admin')

@section('title', $projet->titre)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.projets.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour aux projets
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        @if ($projet->image_url)
            <img src="{{ $projet->image_url }}" alt="{{ $projet->titre }}"
                 class="img-fluid rounded-3 mb-4 w-100" style="max-height:320px;object-fit:cover;">
        @endif

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-2 mb-3">
                    <h4 class="fw-bold flex-grow-1 mb-0">{{ $projet->titre }}</h4>
                    @php
                        $badges = ['brouillon'=>['secondary','Brouillon'],'en_attente'=>['warning','En attente'],'valide'=>['info','Validé'],'en_financement'=>['primary','En financement'],'finance'=>['success','Financé'],'en_cours'=>['success','En cours'],'termine'=>['dark','Terminé'],'rejete'=>['danger','Rejeté']];
                        [$bc,$bl] = $badges[$projet->statut] ?? ['secondary','—'];
                    @endphp
                    <span class="badge bg-{{ $bc }} flex-shrink-0">{{ $bl }}</span>
                </div>
                <div class="small text-muted mb-3">
                    Soumis par <strong>{{ $projet->porteur->nom_complet }}</strong> le {{ $projet->created_at->translatedFormat('d F Y') }}
                    @if ($projet->validateur)
                        · validé par <strong>{{ $projet->validateur->nom_complet }}</strong>
                    @endif
                </div>
                <div style="white-space:pre-line;line-height:1.8;">{{ $projet->description }}</div>

                @if ($projet->motif_rejet)
                    <div class="alert alert-danger mt-3 mb-0">
                        <strong>Motif de rejet :</strong> {{ $projet->motif_rejet }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Documents --}}
        @php $documents = $projet->getMedia('documents'); @endphp
        @if ($documents->isNotEmpty())
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-paperclip me-1 text-faaci-steel"></i> Documents joints
                </div>
                <div class="card-body pt-0">
                    @foreach ($documents as $doc)
                        <a href="{{ $doc->getUrl() }}" target="_blank"
                           class="d-flex align-items-center gap-2 py-2 text-decoration-none text-dark border-bottom">
                            <i class="bi bi-file-earmark-{{ str_ends_with($doc->file_name, '.pdf') ? 'pdf text-danger' : 'word text-primary' }} fs-5"></i>
                            <span class="small flex-grow-1">{{ $doc->file_name }}</span>
                            <span class="text-muted small">{{ round($doc->size / 1024) }} Ko</span>
                            <i class="bi bi-download text-muted small"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Contributions --}}
        @php
            $statutFiltreActif = request('statut_filtre', 'tous');
            $contributionsFiltrees = $projet->contributions->when(
                $statutFiltreActif !== 'tous',
                fn ($c) => $c->where('statut', $statutFiltreActif)
            );
        @endphp
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white pt-3 border-bottom">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <span class="fw-semibold">
                        <i class="bi bi-people me-1 text-faaci-steel"></i>
                        Contributeurs ({{ $contributionsFiltrees->count() }})
                    </span>
                    <div class="d-flex gap-2 flex-wrap">
                        {{-- Export --}}
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-download me-1"></i>Exporter
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width:260px;">
                                <li><h6 class="dropdown-header text-muted">Filtrer l'export par statut</h6></li>
                                @php
                                    $statutsExport = ['tous' => 'Tous'] + \App\Models\Contribution::statutsLibelles();
                                @endphp
                                @foreach ($statutsExport as $val => $lib)
                                    <li class="px-3 py-1">
                                        <div class="fw-medium small mb-1">{{ $lib }}</div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.projets.export.pdf', [$projet, 'statut' => $val]) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-danger py-0 px-2"
                                               style="font-size:.72rem;">
                                                <i class="bi bi-filetype-pdf me-1"></i>PDF
                                            </a>
                                            <a href="{{ route('admin.projets.export.csv', [$projet, 'statut' => $val]) }}"
                                               class="btn btn-sm btn-outline-success py-0 px-2"
                                               style="font-size:.72rem;">
                                                <i class="bi bi-filetype-csv me-1"></i>CSV / Excel
                                            </a>
                                        </div>
                                    </li>
                                    @if (!$loop->last)<li><hr class="dropdown-divider my-1"></li>@endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- Filtre statut (affichage page) --}}
                <div class="d-flex flex-wrap gap-1 mt-2">
                    @foreach (['tous' => 'Tous'] + \App\Models\Contribution::statutsLibelles() as $val => $lib)
                        <a href="{{ request()->fullUrlWithQuery(['statut_filtre' => $val]) }}#contributions"
                           class="badge text-decoration-none {{ $statutFiltreActif === $val ? 'bg-faaci-navy' : 'bg-light text-dark border' }}">
                            {{ $lib }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="card-body pt-0" id="contributions">
                @forelse ($contributionsFiltrees as $c)
                    <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                        <div class="flex-grow-1">
                            <div class="fw-medium small">{{ $c->contributeur->nom_complet }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">
                                {{ number_format($c->montant_promis,0,',',' ') }} FCFA promis
                                @if ($c->montant_paye > 0)
                                    · {{ number_format($c->montant_paye,0,',',' ') }} FCFA déclarés
                                @endif
                                @if ($c->moyen_paiement)
                                    · {{ \App\Models\Contribution::moyensPaiement()[$c->moyen_paiement] }}
                                @endif
                            </div>
                        </div>
                        <span class="badge bg-{{ \App\Models\Contribution::statutsBadge()[$c->statut] ?? 'secondary' }}">
                            {{ \App\Models\Contribution::statutsLibelles()[$c->statut] ?? $c->statut }}
                        </span>
                        <a href="{{ route('admin.contributions.show', $c) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                @empty
                    <p class="text-muted small mb-0 py-2">Aucun contributeur pour ce filtre.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Financement --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                @if ($projet->montant_cible)
                    <div class="text-center mb-3">
                        <div class="fs-4 fw-bold text-faaci-navy">{{ number_format($projet->montant_collecte,0,',',' ') }} FCFA</div>
                        <div class="text-muted small">sur {{ number_format($projet->montant_cible,0,',',' ') }} FCFA</div>
                    </div>
                    <div class="progress mb-2" style="height:8px;">
                        <div class="progress-bar" style="width:{{ $projet->pourcentage }}%;background:var(--faaci-steel);"></div>
                    </div>
                    <div class="text-center small text-muted">{{ $projet->pourcentage }} % atteint</div>
                @else
                    <div class="text-center">
                        <div class="fs-4 fw-bold text-faaci-navy">{{ number_format($projet->montant_collecte,0,',',' ') }} FCFA</div>
                        <div class="text-muted small">Financement ouvert</div>
                    </div>
                @endif
                @if ($projet->date_fin_financement)
                    <hr>
                    <div class="text-center small text-muted">Fin financement : {{ $projet->date_fin_financement->translatedFormat('d F Y') }}</div>
                @endif
            </div>
        </div>

        {{-- Actions admin --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">Actions</div>
            <div class="card-body pt-0 d-grid gap-2">
                @if (in_array($projet->statut, ['en_attente', 'valide']))
                    <form method="POST" action="{{ route('admin.projets.valider', $projet) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-lg me-1"></i> Mettre en financement
                        </button>
                    </form>
                @endif

                @if (in_array($projet->statut, ['en_attente', 'valide']))
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modalRejeter">
                        <i class="bi bi-x-lg me-1"></i> Rejeter
                    </button>
                @endif

                @if (in_array($projet->statut, ['en_financement','finance','en_cours']))
                    <form method="POST" action="{{ route('admin.projets.statut', $projet) }}">
                        @csrf @method('PATCH')
                        <select name="statut" class="form-select form-select-sm mb-2">
                            <option value="en_financement" {{ $projet->statut==='en_financement'?'selected':'' }}>En financement</option>
                            <option value="finance" {{ $projet->statut==='finance'?'selected':'' }}>Financé</option>
                            <option value="en_cours" {{ $projet->statut==='en_cours'?'selected':'' }}>En cours</option>
                            <option value="termine" {{ $projet->statut==='termine'?'selected':'' }}>Terminé</option>
                        </select>
                        <button type="submit" class="btn btn-outline-secondary w-100 btn-sm">
                            Changer le statut
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal rejet --}}
<div class="modal fade" id="modalRejeter" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Rejeter le projet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.projets.rejeter', $projet) }}">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <label for="motif_rejet" class="form-label">Motif de rejet <span class="text-danger">*</span></label>
                    <textarea id="motif_rejet" name="motif_rejet" rows="3" class="form-control" required
                              placeholder="Expliquez pourquoi le projet est rejeté…"></textarea>
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
