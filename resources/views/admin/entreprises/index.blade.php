@extends('layouts.admin')

@section('title', 'Entreprises Alumni')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Annuaire entreprises Alumni</h4>
</div>

<ul class="nav nav-tabs mb-3" id="entrepriseTabs">
    <li class="nav-item">
        <a class="nav-link active" href="#" data-statut="tous">
            Tous <span class="badge bg-secondary ms-1">{{ $compteurs['tous'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="en_attente">
            En attente <span class="badge bg-warning text-dark ms-1">{{ $compteurs['en_attente'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="actif">
            Actives <span class="badge bg-success ms-1">{{ $compteurs['actif'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="rejete">
            Rejetées <span class="badge bg-danger ms-1">{{ $compteurs['rejete'] }}</span>
        </a>
    </li>
</ul>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table id="entreprisesTable" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Entreprise</th>
                    <th>Secteur</th>
                    <th>Localisation</th>
                    <th>Propriétaire</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentStatut = 'tous';

    const table = $('#entreprisesTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json' },
        ajax: {
            url: '{{ route('admin.entreprises.index') }}',
            data: d => { d.statut = currentStatut; }
        },
        columns: [
            { data: 'id', width: '5%' },
            { data: 'nom' },
            { data: 'secteur', defaultContent: '—' },
            { data: 'localisation', defaultContent: '—' },
            { data: 'proprietaire' },
            { data: 'statut_badge', orderable: false },
            { data: 'created_at', render: d => d ? d.substring(0,10) : '—' },
            { data: 'actions', orderable: false, searchable: false, className: 'text-end' },
        ],
        order: [[0, 'desc']],
    });

    document.querySelectorAll('#entrepriseTabs .nav-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('#entrepriseTabs .nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            currentStatut = this.dataset.statut;
            table.ajax.reload();
        });
    });
});
</script>
@endpush
