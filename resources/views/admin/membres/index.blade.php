@extends('layouts.admin')

@section('title', 'Gestion des membres')
@section('page-title', 'Gestion des membres')


@section('content')
    <p class="text-muted">Gérez les demandes d'adhésion et le statut des membres de la plateforme.</p>

    <ul class="nav nav-pills mb-3 flex-wrap" id="statutTabs">
        <li class="nav-item">
            <a class="nav-link active" href="#" data-statut="tous">Tous <span class="badge bg-light text-dark ms-1">{{ $compteurs['tous'] }}</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-statut="en_attente">En attente <span class="badge bg-warning text-dark ms-1">{{ $compteurs['en_attente'] }}</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-statut="actif">Actifs <span class="badge bg-success ms-1">{{ $compteurs['actif'] }}</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-statut="suspendu">Suspendus <span class="badge bg-secondary ms-1">{{ $compteurs['suspendu'] }}</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-statut="inactif">Inactifs <span class="badge bg-dark ms-1">{{ $compteurs['inactif'] }}</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-statut="rejete">Rejetés <span class="badge bg-danger ms-1">{{ $compteurs['rejete'] }}</span></a>
        </li>
    </ul>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0 p-md-3">
            <table class="table align-middle mb-0" id="membres-table" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Membre</th>
                        <th>Téléphone</th>
                        <th>Rôle(s)</th>
                        <th>Statut</th>
                        <th>Inscrit le</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let statutActuel = 'tous';

    const table = new DataTable('#membres-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json' },
        ajax: {
            url: '{{ route('admin.membres.index') }}',
            data: function (d) { d.statut = statutActuel; },
        },
        order: [[4, 'desc']],
        columns: [
            { data: 'membre',      name: 'nom',       responsivePriority: 1 },
            { data: 'telephone',   name: 'telephone',  defaultContent: '—', responsivePriority: 5 },
            { data: 'roles',       name: 'roles',      orderable: false, searchable: false, responsivePriority: 4 },
            { data: 'statut_badge',name: 'statut',     responsivePriority: 2 },
            { data: 'created_at',  name: 'created_at', responsivePriority: 3 },
            { data: 'actions',     name: 'actions',    orderable: false, searchable: false, className: 'text-end', responsivePriority: 1 },
        ],
    });

    document.querySelectorAll('#statutTabs .nav-link').forEach((tab) => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('#statutTabs .nav-link').forEach((t) => t.classList.remove('active'));
            this.classList.add('active');
            statutActuel = this.dataset.statut;
            table.ajax.reload();
        });
    });
</script>
@endpush
