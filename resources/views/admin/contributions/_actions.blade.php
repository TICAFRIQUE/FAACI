<div class="d-flex gap-1 justify-content-end">
    <a href="{{ route('admin.contributions.show', $c) }}"
       class="btn btn-sm btn-outline-secondary" title="Voir le détail">
        <i class="bi bi-eye"></i>
    </a>
    @if (in_array($c->statut, [\App\Models\Contribution::STATUT_PROMESSE, \App\Models\Contribution::STATUT_PARTIEL]))
        <a href="{{ route('admin.contributions.show', $c) }}"
           class="btn btn-sm btn-success" title="Enregistrer un paiement">
            <i class="bi bi-cash-coin"></i>
        </a>
    @endif
</div>
