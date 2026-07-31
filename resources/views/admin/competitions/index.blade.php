@extends('layouts.admin')

@section('title', 'Compétitions Alumni')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Compétitions Alumni</h4>
    <a href="{{ route('admin.competitions.create') }}" class="btn btn-faaci-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Nouvelle compétition
    </a>
</div>

<ul class="nav nav-tabs mb-3" id="competitionTabs">
    <li class="nav-item">
        <a class="nav-link active" href="#" data-statut="tous">
            Tous <span class="badge bg-secondary ms-1">{{ $compteurs['tous'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="ouverte">
            Ouvertes <span class="badge bg-success ms-1">{{ $compteurs['ouverte'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="brouillon">
            Brouillons <span class="badge bg-secondary ms-1">{{ $compteurs['brouillon'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="cloturee">
            Clôturées <span class="badge bg-warning ms-1">{{ $compteurs['cloturee'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="terminee">
            Terminées <span class="badge bg-dark ms-1">{{ $compteurs['terminee'] }}</span>
        </a>
    </li>
</ul>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table id="competitionsTable" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light">
                <tr>
                    <th>Titre</th>
                    <th>Budget</th>
                    <th>Date limite</th>
                    <th>Candidatures</th>
                    <th>Statut</th>
                    <th>Créée le</th>
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
const table = $('#competitionsTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route('admin.competitions.index') }}',
        data: d => { d.statut = currentStatut; }
    },
    columns: [
        { data: 'titre', name: 'titre' },
        { data: 'budget_fmt', name: 'budget', orderable: false },
        { data: 'date_limite_fmt', name: 'date_limite_candidature' },
        { data: 'candidatures_count', name: 'candidatures_count', className: 'text-center' },
        { data: 'statut_badge', name: 'statut', orderable: false },
        { data: 'created_at', name: 'created_at', render: d => new Date(d).toLocaleDateString('fr-FR') },
        { data: 'actions', orderable: false, searchable: false, className: 'text-end' },
    ],
    order: [[5, 'desc']],
    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' },
    dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>rt<'row'<'col-sm-5'i><'col-sm-7'p>>",
});

document.querySelectorAll('#competitionTabs .nav-link').forEach(link => {
    link.addEventListener('click', e => {
        e.preventDefault();
        document.querySelectorAll('#competitionTabs .nav-link').forEach(l => l.classList.remove('active'));
        link.classList.add('active');
        currentStatut = link.dataset.statut;
        table.ajax.reload();
    });
});
</script>
@endpush
