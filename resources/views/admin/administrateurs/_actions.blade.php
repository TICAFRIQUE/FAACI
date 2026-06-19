<div class="d-flex justify-content-end gap-1">
    <a href="{{ route('admin.administrateurs.show', $utilisateur) }}"
       class="btn btn-sm btn-outline-secondary" title="Voir">
        <i class="bi bi-eye"></i>
    </a>
    <a href="{{ route('admin.administrateurs.edit', $utilisateur) }}"
       class="btn btn-sm btn-outline-primary" title="Modifier">
        <i class="bi bi-pencil"></i>
    </a>
</div>
