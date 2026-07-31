@extends('layouts.admin')
@section('title', 'Paiements cotisation')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Paiements de cotisation</h4>
</div>

<ul class="nav nav-tabs mb-3" id="paiementTabs">
    <li class="nav-item"><a class="nav-link active" href="#" data-statut="tous">Tous <span class="badge bg-secondary ms-1">{{ $compteurs['tous'] }}</span></a></li>
    <li class="nav-item"><a class="nav-link" href="#" data-statut="en_attente">À valider <span class="badge bg-warning text-dark ms-1">{{ $compteurs['en_attente'] }}</span></a></li>
    <li class="nav-item"><a class="nav-link" href="#" data-statut="valide">Validés <span class="badge bg-success ms-1">{{ $compteurs['valide'] }}</span></a></li>
    <li class="nav-item"><a class="nav-link" href="#" data-statut="rejete">Rejetés <span class="badge bg-danger ms-1">{{ $compteurs['rejete'] }}</span></a></li>
</ul>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table id="paiementsTable" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light">
                <tr>
                    <th>#</th><th>Membre</th><th>Type</th><th>Périodes</th>
                    <th>Montant</th><th>Saisi par</th><th>Statut</th><th>Date</th><th></th>
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
    const table = $('#paiementsTable').DataTable({
        processing: true, serverSide: true, responsive: true,
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json' },
        ajax: { url: '{{ route('admin.cotisations.paiements.index') }}', data: d => { d.statut = currentStatut; } },
        columns: [
            { data: 'id',           width: '5%' },
            { data: 'membre' },
            { data: 'type_nom' },
            { data: 'periodes_fmt', orderable: false },
            { data: 'montant_fmt',  searchable: false },
            { data: 'saisi_par_nom',orderable: false, searchable: false },
            { data: 'statut_badge', orderable: false, searchable: false },
            { data: 'date_fmt',     searchable: false },
            { data: 'actions',      orderable: false, searchable: false, className: 'text-end' },
        ],
        order: [[0, 'desc']],
    });
    document.querySelectorAll('#paiementTabs .nav-link').forEach(l => {
        l.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('#paiementTabs .nav-link').forEach(x => x.classList.remove('active'));
            this.classList.add('active');
            currentStatut = this.dataset.statut;
            table.ajax.reload();
        });
    });
});
</script>
@endpush
