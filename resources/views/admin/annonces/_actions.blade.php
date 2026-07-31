<div class="d-flex gap-1 justify-content-end">
    <a href="{{ route('admin.annonces.edit', $a) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
        <i class="bi bi-pencil"></i>
    </a>
    <form method="POST" action="{{ route('admin.annonces.destroy', $a) }}"
          class="m-0" data-confirm="Supprimer cette annonce ?">
        @csrf @method('DELETE')
        <button class="btn btn-sm btn-outline-danger" title="Supprimer">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>
