@extends('layouts.admin')

@section('title', 'Contributions')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Contributions & Paiements</h4>
</div>

<ul class="nav nav-tabs mb-3" id="contribTabs">
    <li class="nav-item">
        <a class="nav-link active" href="#" data-statut="tous">
            Tous <span class="badge bg-secondary ms-1">{{ $compteurs['tous'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="confirmed">
            À valider <span class="badge bg-info ms-1">{{ $compteurs['confirmed'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="partial">
            Partiels <span class="badge bg-primary ms-1">{{ $compteurs['partial'] ?? 0 }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="paid">
            Validés <span class="badge bg-success ms-1">{{ $compteurs['paid'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="pending">
            En attente <span class="badge bg-warning text-dark ms-1">{{ $compteurs['pending'] }}</span>
        </a>
    </li>
</ul>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0 p-md-3">
        <table id="contribTable" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light">
                <tr>
                    <th>Contributeur</th>
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

{{-- Modal rejet rapide --}}
<div class="modal fade" id="modalRejeterContrib" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Rejeter la déclaration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRejeterContrib" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-info small mb-3">Le membre devra re-déclarer son paiement.</div>
                    <label for="motif_rejet_quick" class="form-label">Motif <span class="text-danger">*</span></label>
                    <textarea id="motif_rejet_quick" name="motif_rejet" rows="3"
                              class="form-control" required
                              placeholder="Ex : Montant incorrect, capture illisible…"></textarea>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter</button>
                </div>
            </form>
        </div>
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
        { data: 'contributeur',       name: 'contributeur',  orderable: false,                   responsivePriority: 2 },
        { data: 'projet',             name: 'projet',        orderable: false,                   responsivePriority: 3 },
        { data: 'montant_promis_fmt', name: 'montant_promis', searchable: false,                 responsivePriority: 4 },
        { data: 'montant_paye_fmt',   name: 'montant_paye',  searchable: false,                  responsivePriority: 5 },
        { data: 'moyen',              name: 'moyen_paiement', orderable: false, searchable: false, responsivePriority: 6 },
        { data: 'statut_badge',       name: 'statut',        orderable: false, searchable: false, responsivePriority: 2 },
        { data: 'created_at',         name: 'created_at',    searchable: false,                  responsivePriority: 5 },
        { data: 'actions',            name: 'actions',       orderable: false, searchable: false, responsivePriority: 1 },
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

function rejeterContrib(id, url) {
    document.getElementById('formRejeterContrib').action = url;
    document.getElementById('motif_rejet_quick').value = '';
    new bootstrap.Modal(document.getElementById('modalRejeterContrib')).show();
}
</script>
@endpush
