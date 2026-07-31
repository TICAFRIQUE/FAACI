<a href="{{ route('admin.competitions.show', $c) }}" class="btn btn-sm btn-outline-secondary" title="Voir">
    <i class="bi bi-eye"></i>
</a>
<a href="{{ route('admin.competitions.edit', $c) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
    <i class="bi bi-pencil"></i>
</a>
<a href="{{ route('admin.competitions.candidatures', $c) }}" class="btn btn-sm btn-outline-info" title="Candidatures">
    <i class="bi bi-people"></i>
    @if ($c->candidatures_count > 0)
        <span class="badge bg-info text-dark ms-1">{{ $c->candidatures_count }}</span>
    @endif
</a>
<form method="POST" action="{{ route('admin.competitions.destroy', $c) }}" class="d-inline">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger"
            data-confirm="Supprimer cette compétition ? Les candidatures associées seront également supprimées.">
        <i class="bi bi-trash"></i>
    </button>
</form>
