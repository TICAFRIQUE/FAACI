@extends('layouts.admin')

@section('title', 'Dons à la fondation')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Dons à la fondation</h4>
</div>

<ul class="nav nav-tabs mb-3" id="donsTabs">
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
        <a class="nav-link" href="#" data-statut="confirme">
            Confirmés <span class="badge bg-success ms-1">{{ $compteurs['confirme'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="rejete">
            Rejetés <span class="badge bg-danger ms-1">{{ $compteurs['rejete'] }}</span>
        </a>
    </li>
</ul>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table id="donsTable" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Donateur</th>
                    <th>Nature</th>
                    <th>Libellé</th>
                    <th>Montant / Valeur</th>
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

    const table = $('#donsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json' },
        ajax: {
            url: '{{ route('admin.dons.index') }}',
            data: d => { d.statut = currentStatut; }
        },
        columns: [
            { data: 'id', width: '5%' },
            { data: 'donateur' },
            { data: 'nature_libelle' },
            { data: 'libelle' },
            { data: 'montant_fmt' },
            { data: 'statut_badge', orderable: false },
            { data: 'created_at', render: d => d ? d.substring(0,10) : '—' },
            { data: 'actions', orderable: false, searchable: false, className: 'text-end' },
        ],
        order: [[0, 'desc']],
    });

    document.querySelectorAll('#donsTabs .nav-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('#donsTabs .nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            currentStatut = this.dataset.statut;
            table.ajax.reload();
        });
    });
});
</script>
@endpush
