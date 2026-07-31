<a href="{{ route('admin.cotisations.paiements.show', $p) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
@if ($p->statut === 'en_attente')
    <form method="POST" action="{{ route('admin.cotisations.paiements.valider', $p) }}" class="d-inline" data-confirm="Valider ce paiement de {{ number_format($p->montant, 0, ',', ' ') }} FCFA ?">
        @csrf @method('PATCH')
        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
    </form>
    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejetPaiementModal{{ $p->id }}">
        <i class="bi bi-x-lg"></i>
    </button>
    <div class="modal fade" id="rejetPaiementModal{{ $p->id }}" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <form method="POST" action="{{ route('admin.cotisations.paiements.rejeter', $p) }}">
                @csrf @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header"><h6 class="modal-title">Rejeter le paiement #{{ $p->id }}</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body"><textarea name="motif_rejet" rows="3" class="form-control form-control-sm" required maxlength="500" placeholder="Motif de rejet…"></textarea></div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-danger btn-sm">Rejeter</button></div>
                </div>
            </form>
        </div>
    </div>
@endif
