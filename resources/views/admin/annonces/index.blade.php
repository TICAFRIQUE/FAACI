@extends('layouts.admin')
@section('title', 'Annonces membres')
@section('page-title', 'Annonces membres')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-muted mb-0">Messages et informations envoyés à tous les membres actifs.</p>
        <a href="{{ route('admin.annonces.create') }}" class="btn btn-faaci-navy">
            <i class="bi bi-plus-lg me-1"></i> Nouvelle annonce
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Publiée le</th>
                        <th>Expire le</th>
                        <th>Auteur</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($annonces as $annonce)
                        @php $cfg = $annonce->type_config; @endphp
                        <tr>
                            <td class="fw-semibold">{{ $annonce->titre }}</td>
                            <td>
                                <span class="badge bg-{{ $cfg['couleur'] }} bg-opacity-10 text-{{ $cfg['couleur'] }}">
                                    <i class="bi {{ $cfg['icone'] }} me-1"></i>{{ $cfg['libelle'] }}
                                </span>
                            </td>
                            <td>
                                @if ($annonce->statut === 'publiee')
                                    <span class="badge bg-success">Publiée</span>
                                @elseif ($annonce->statut === 'archivee')
                                    <span class="badge bg-secondary">Archivée</span>
                                @else
                                    <span class="badge bg-warning text-dark">Brouillon</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $annonce->publiee_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="text-muted small">{{ $annonce->expire_at?->format('d/m/Y') ?? '—' }}</td>
                            <td class="text-muted small">{{ $annonce->auteur?->nom_complet ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.annonces.edit', $annonce) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.annonces.destroy', $annonce) }}"
                                      class="d-inline" onsubmit="return confirm('Supprimer cette annonce ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Aucune annonce créée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $annonces->links() }}</div>
@endsection
