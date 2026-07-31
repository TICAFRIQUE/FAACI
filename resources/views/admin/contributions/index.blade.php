@extends('layouts.admin')
@section('title', 'Investissements')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Investissements & Paiements</h4>
</div>

<ul class="nav nav-tabs mb-3" id="contribTabs">
    <li class="nav-item">
        <a class="nav-link active" href="#" data-statut="tous">
            Tous <span class="badge bg-secondary ms-1">{{ $compteurs['tous'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="promesse">
            Promesses <span class="badge bg-warning text-dark ms-1">{{ $compteurs['promesse'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="partiel">
            Partiels <span class="badge bg-primary ms-1">{{ $compteurs['partiel'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="paye">
            Payés <span class="badge bg-success ms-1">{{ $compteurs['paye'] }}</span>
        </a>
    </li>
</ul>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0 p-md-3">
        <table id="contribTable" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light">
                <tr>
                    <th>Membre</th>
                    <th>Projet</th>
                    <th>Promis</th>
                    <th>Payé</th>
                    <th>Moyen</th>
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
let statutActif = 'tous';

const table = $('#contribTable').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
        url: '{{ route('admin.contributions.index') }}',
        data: d => { d.statut = statutActif; }
    },
    columns: [
        { data: 'contributeur',       orderable: false,                    responsivePriority: 2 },
        { data: 'projet',             orderable: false,                    responsivePriority: 3 },
        { data: 'montant_promis_fmt', searchable: false,                   responsivePriority: 4 },
        { data: 'montant_paye_fmt',   searchable: false,                   responsivePriority: 5 },
        { data: 'moyen',              orderable: false, searchable: false,  responsivePriority: 6 },
        { data: 'statut_badge',       orderable: false, searchable: false,  responsivePriority: 2 },
        { data: 'date_fmt',           searchable: false,                   responsivePriority: 5 },
        { data: 'actions',            orderable: false, searchable: false,  responsivePriority: 1, className: 'text-end' },
    ],
    order: [[6, 'desc']],
    language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },
});

document.querySelectorAll('#contribTabs .nav-link').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelectorAll('#contribTabs .nav-link').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        statutActif = this.dataset.statut;
        table.ajax.reload();
    });
});
</script>
@endpush
