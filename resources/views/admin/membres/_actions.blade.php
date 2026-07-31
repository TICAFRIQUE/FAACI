@php
    $user = \App\Models\User::class;
@endphp
<div class="d-flex justify-content-end gap-1">
    <a href="{{ route('admin.membres.show', $membre) }}" class="btn btn-sm btn-outline-secondary" title="Voir">
        <i class="bi bi-eye"></i>
    </a>

    @if ($membre->statut === $user::STATUT_EN_ATTENTE)
        <form method="POST" action="{{ route('admin.membres.valider', $membre) }}" data-confirm="Valider cette demande d'adhésion ?">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-success" title="Valider">
                <i class="bi bi-check-lg"></i>
            </button>
        </form>
    @endif
    @hasrole('super_admin')
    <a href="{{ route('admin.membres.edit', $membre) }}"
       class="btn btn-sm btn-outline-primary" title="Modifier">
        <i class="bi bi-pencil"></i>
    </a>
    @endhasrole
</div>
