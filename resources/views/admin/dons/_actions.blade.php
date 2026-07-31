<a href="{{ route('admin.dons.show', $d) }}" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-eye"></i>
</a>
@if ($d->statut === 'en_attente')
    <form method="POST" action="{{ route('admin.dons.confirmer', $d) }}" class="d-inline">
        @csrf @method('PATCH')
        <button type="submit" class="btn btn-sm btn-success">
            <i class="bi bi-check-lg"></i>
        </button>
    </form>
    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejetModal{{ $d->id }}">
        <i class="bi bi-x-lg"></i>
    </button>

    {{-- Modal rejet --}}
    <div class="modal fade" id="rejetModal{{ $d->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.dons.rejeter', $d) }}">
                @csrf @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rejeter le don #{{ $d->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Motif de rejet <span class="text-danger">*</span></label>
                        <textarea name="motif_rejet" rows="3" class="form-control" required maxlength="500"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger btn-sm">Rejeter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif
