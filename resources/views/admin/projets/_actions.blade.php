<div class="d-flex gap-1 flex-wrap">
    <a href="{{ route('admin.projets.show', $p) }}"
       class="btn btn-sm btn-outline-secondary" title="Voir le détail">
        <i class="bi bi-eye"></i>
    </a>
    @if (in_array($p->statut, ['en_attente', 'valide']))
        <form method="POST" action="{{ route('admin.projets.valider', $p) }}" class="m-0">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-sm btn-success" title="Mettre en financement"
                    onclick="return confirm('Mettre ce projet en financement ?')">
                <i class="bi bi-check-lg"></i>
            </button>
        </form>
        <button type="button" class="btn btn-sm btn-outline-danger" title="Rejeter"
                onclick="rejeterProjet({{ $p->id }}, '{{ route('admin.projets.rejeter', $p) }}', '{{ addslashes($p->titre) }}')">
            <i class="bi bi-x-lg"></i>
        </button>
    @endif
    @if (in_array($p->statut, ['en_financement', 'finance', 'en_cours']))
        <button type="button" class="btn btn-sm btn-outline-primary" title="Changer le statut"
                onclick="changerStatut({{ $p->id }}, '{{ route('admin.projets.statut', $p) }}', '{{ $p->statut }}')">
            <i class="bi bi-arrow-repeat"></i>
        </button>
    @endif
</div>
