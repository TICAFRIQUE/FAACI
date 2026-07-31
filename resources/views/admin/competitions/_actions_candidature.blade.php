{{-- Bouton Voir --}}
<button type="button" class="btn btn-sm btn-outline-info"
        data-bs-toggle="modal"
        data-bs-target="#modalVoirCandidature{{ $c->id }}">
    <i class="bi bi-eye"></i>
</button>

{{-- Bouton Statut --}}
<button type="button" class="btn btn-sm btn-outline-secondary"
        data-bs-toggle="modal"
        data-bs-target="#modalCandidature{{ $c->id }}">
    <i class="bi bi-pencil-square"></i>
</button>

{{-- Modal Voir détail --}}
<div class="modal fade" id="modalVoirCandidature{{ $c->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-person-circle me-1"></i> {{ $c->candidat?->nom_complet }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @php
                    $sb = \App\Models\CandidatureCompetition::statutsBadge()[$c->statut] ?? 'secondary';
                    $sl = \App\Models\CandidatureCompetition::statuts()[$c->statut] ?? $c->statut;
                @endphp
                <div class="mb-3">
                    <span class="badge bg-{{ $sb }}">{{ $sl }}</span>
                    <span class="text-muted small ms-2">Soumise le {{ $c->created_at->translatedFormat('d F Y à H:i') }}</span>
                </div>

                <div class="mb-3">
                    <div class="text-muted small fw-semibold text-uppercase mb-1" style="letter-spacing:.06em;">Titre du projet</div>
                    <div class="fw-semibold fs-6">{{ $c->titre_projet }}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small fw-semibold text-uppercase mb-1" style="letter-spacing:.06em;">Résumé du projet</div>
                    <div class="p-3 rounded" style="background:#f8fafc;white-space:pre-line;font-size:0.92rem;line-height:1.7;">{{ $c->resume_projet }}</div>
                </div>

                @if ($c->note_jury)
                    <div class="alert alert-secondary mb-0">
                        <div class="fw-semibold small mb-1"><i class="bi bi-chat-quote me-1"></i> Note du jury</div>
                        <div style="white-space:pre-line;">{{ $c->note_jury }}</div>
                    </div>
                @endif

                {{-- Infos candidat --}}
                <hr>
                <div class="row g-2 small text-muted">
                    <div class="col-sm-6">
                        <i class="bi bi-envelope me-1"></i> {{ $c->candidat?->email ?? '—' }}
                    </div>
                    <div class="col-sm-6">
                        <i class="bi bi-telephone me-1"></i> {{ $c->candidat?->telephone ?? '—' }}
                    </div>
                    @if ($c->candidat?->comite_local)
                        <div class="col-sm-6">
                            <i class="bi bi-geo-alt me-1"></i> {{ $c->candidat->comite_local }}
                        </div>
                    @endif
                    @if ($c->candidat?->promotion)
                        <div class="col-sm-6">
                            <i class="bi bi-mortarboard me-1"></i> Promotion {{ $c->candidat->promotion }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-faaci-primary btn-sm"
                        data-bs-dismiss="modal"
                        data-bs-toggle="modal"
                        data-bs-target="#modalCandidature{{ $c->id }}">
                    <i class="bi bi-pencil-square me-1"></i> Changer le statut
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Statut --}}
<div class="modal fade" id="modalCandidature{{ $c->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.competitions.candidatures.maj', [$competition, $c]) }}">
                @csrf @method('PATCH')
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Statut — {{ $c->candidat?->nom_complet }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="text-muted small mb-2 fst-italic">{{ $c->titre_projet }}</div>
                        <label class="form-label fw-semibold small">Nouveau statut <span class="text-danger">*</span></label>
                        <select name="statut" class="form-select" required>
                            @foreach (\App\Models\CandidatureCompetition::statuts() as $val => $lib)
                                <option value="{{ $val }}" {{ $c->statut === $val ? 'selected' : '' }}>{{ $lib }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold small">Note du jury (optionnel)</label>
                        <textarea name="note_jury" rows="3" class="form-control form-control-sm"
                                  maxlength="1000" placeholder="Commentaire visible par le candidat…">{{ $c->note_jury }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-faaci-primary btn-sm">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
