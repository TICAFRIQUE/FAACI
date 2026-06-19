@extends('layouts.admin')

@section('title', 'Administrateurs')
@section('page-title', 'Administrateurs')


@section('content')
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
        <p class="text-muted mb-0">Gérez les comptes admin et super admin de la plateforme.</p>
        <a href="{{ route('admin.administrateurs.create') }}" class="btn btn-faaci-navy">
            <i class="bi bi-plus-lg me-1"></i>Nouvel administrateur
        </a>
    </div>

    <ul class="nav nav-pills mb-3 flex-wrap" id="statutTabs">
        <li class="nav-item">
            <a class="nav-link active" href="#" data-statut="tous">
                Tous <span class="badge bg-light text-dark ms-1">{{ $compteurs['tous'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-statut="actif">
                Actifs <span class="badge bg-success ms-1">{{ $compteurs['actif'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-statut="suspendu">
                Suspendus <span class="badge bg-secondary ms-1">{{ $compteurs['suspendu'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-statut="inactif">
                Inactifs <span class="badge bg-dark ms-1">{{ $compteurs['inactif'] }}</span>
            </a>
        </li>
    </ul>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0 p-md-3">
            <table class="table align-middle mb-0" id="administrateurs-table" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Administrateur</th>
                        <th>Téléphone</th>
                        <th>Rôle(s)</th>
                        <th>Statut</th>
                        <th>Créé le</th>
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

    const table = new DataTable('#administrateurs-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json' },
        ajax: {
            url: '{{ route('admin.administrateurs.index') }}',
            data: function (d) { d.statut = statutActuel; },
        },
        order: [[4, 'desc']],
        columns: [
            { data: 'membre',       name: 'nom',      responsivePriority: 1 },
            { data: 'telephone',    name: 'telephone', defaultContent: '—', responsivePriority: 5 },
            { data: 'roles',        name: 'roles',     orderable: false, searchable: false, responsivePriority: 4 },
            { data: 'statut_badge', name: 'statut',    responsivePriority: 2 },
            { data: 'created_at',   name: 'created_at', responsivePriority: 3 },
            { data: 'actions',      name: 'actions',   orderable: false, searchable: false, className: 'text-end', responsivePriority: 1 },
        ],
    });

    document.querySelectorAll('#statutTabs .nav-link').forEach(function (tab) {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('#statutTabs .nav-link').forEach(function (t) { t.classList.remove('active'); });
            this.classList.add('active');
            statutActuel = this.dataset.statut;
            table.ajax.reload();
        });
    });
</script>
@endpush
