@extends('layouts.admin')

@section('title', 'Actualités')
@section('page-title', 'Actualités')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">Gérez les articles d'actualité affichés sur le site public.</p>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-faaci-navy">
            <i class="bi bi-plus-lg me-1"></i> Nouvel article
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:90px;">Image</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Publication</th>
                        <th class="text-center" style="width:110px;">Statut</th>
                        <th class="text-end" style="width:110px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($articles as $article)
                        <tr>
                            <td>
                                @if ($article->image_url)
                                    <img src="{{ $article->image_url }}" alt="" class="rounded" style="width:64px; height:40px; object-fit:cover;">
                                @else
                                    <div class="bg-faaci-gray rounded d-flex align-items-center justify-content-center" style="width:64px; height:40px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $article->titre }}</td>
                            <td class="text-muted">{{ $article->categorie }}</td>
                            <td class="text-muted">{{ $article->date_publication?->format('d/m/Y') ?? '—' }}</td>
                            <td class="text-center">
                                <span class="badge {{ $article->statut === \App\Models\Article::STATUT_PUBLIE ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $article->statut === \App\Models\Article::STATUT_PUBLIE ? 'Publié' : 'Brouillon' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" class="d-inline"
                                      data-confirm="Supprimer cet article ?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Aucun article pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($articles->hasPages())
            <div class="card-footer bg-white">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
@endsection
