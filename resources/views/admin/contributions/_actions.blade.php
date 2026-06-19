<div class="d-flex gap-1">
    <a href="{{ route('admin.contributions.show', $c) }}"
       class="btn btn-sm btn-outline-secondary" title="Voir le détail">
        <i class="bi bi-eye"></i>
    </a>
    @if (in_array($c->statut, ['confirmed', 'partial']))
        <form method="POST" action="{{ route('admin.contributions.valider', $c) }}" class="m-0">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-sm btn-success" title="Valider le paiement"
                    onclick="return confirm('Valider ce paiement de {{ number_format($c->montant_paye, 0, ',', ' ') }} FCFA ?')">
                <i class="bi bi-check-lg"></i>
            </button>
        </form>
        <button type="button" class="btn btn-sm btn-outline-danger"
                title="Rejeter"
                onclick="rejeterContrib({{ $c->id }}, '{{ route('admin.contributions.rejeter', $c) }}')">
            <i class="bi bi-x-lg"></i>
        </button>
    @endif
</div>
