@extends('layouts.admin')

@section('title', 'Candidatures — ' . $competition->titre)

@section('content')
<div class="mb-3 d-flex gap-2 align-items-center">
    <a href="{{ route('admin.competitions.show', $competition) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h5 class="fw-bold mb-0">
            <i class="bi bi-trophy me-1 text-faaci-steel"></i> {{ $competition->titre }}
        </h5>
        <small class="text-muted">Gestion des candidatures</small>
    </div>
    @if ($competition->budget)
        <div class="text-end">
            <div class="fw-bold text-faaci-navy" style="font-size:1.2rem;">
                {{ number_format($competition->budget, 0, ',', ' ') }} FCFA
            </div>
            <small class="text-muted">Budget alloué</small>
        </div>
    @endif
</div>

<ul class="nav nav-tabs mb-3" id="candidatureTabs">
    <li class="nav-item">
        <a class="nav-link active" href="#" data-statut="tous">
            Toutes <span class="badge bg-secondary ms-1">{{ $compteurs['tous'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="en_attente">
            En attente <span class="badge bg-secondary ms-1">{{ $compteurs['en_attente'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="selectionnee">
            Sélectionnées <span class="badge bg-info ms-1">{{ $compteurs['selectionnee'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="gagnante">
            Gagnantes <span class="badge bg-success ms-1">{{ $compteurs['gagnante'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="eliminee">
            Éliminées <span class="badge bg-danger ms-1">{{ $compteurs['eliminee'] }}</span>
        </a>
    </li>
</ul>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table id="candidaturesTable" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light">
                <tr>
                    <th>Candidat</th>
                    <th>Titre du projet</th>
                    <th>Résumé</th>
                    <th>Statut</th>
                    <th>Soumise le</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentStatut = 'tous';
const table = $('#candidaturesTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route('admin.competitions.candidatures', $competition) }}',
        data: d => { d.statut = currentStatut; }
    },
    columns: [
        { data: 'candidat_nom', name: 'candidat_nom' },
        { data: 'titre_projet', name: 'titre_projet' },
        {
            data: 'resume_projet', name: 'resume_projet', orderable: false,
            render: d => `<span style="max-width:200px;display:inline-block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${d}">${d}</span>`
        },
        { data: 'statut_badge', name: 'statut', orderable: false },
        { data: 'created_at', name: 'created_at', render: d => new Date(d).toLocaleDateString('fr-FR') },
        { data: 'actions', orderable: false, searchable: false, className: 'text-end' },
    ],
    order: [[4, 'asc']],
    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' },
    dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>rt<'row'<'col-sm-5'i><'col-sm-7'p>>",
});

document.querySelectorAll('#candidatureTabs .nav-link').forEach(link => {
    link.addEventListener('click', e => {
        e.preventDefault();
        document.querySelectorAll('#candidatureTabs .nav-link').forEach(l => l.classList.remove('active'));
        link.classList.add('active');
        currentStatut = link.dataset.statut;
        table.ajax.reload();
    });
});
</script>
@endpush
