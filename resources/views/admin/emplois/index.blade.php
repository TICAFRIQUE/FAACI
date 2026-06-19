@extends('layouts.admin')

@section('title', 'Offres d\'emploi')
@section('page-title', 'Offres d\'emploi')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <p class="text-muted small mb-0">Validez les offres soumises par les membres et publiez vos propres offres.</p>
    <a href="{{ route('admin.emplois.create') }}" class="btn btn-faaci-navy">
        <i class="bi bi-plus-lg me-1"></i> Publier une offre
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm border-start border-secondary border-3 h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Total</div>
                <div class="h3 fw-bold mb-0">{{ $compteurs['tous'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm border-start border-warning border-3 h-100">
            <div class="card-body py-3">
                <div class="text-muted small">En attente</div>
                <div class="h3 fw-bold text-warning mb-0">{{ $compteurs['en_attente'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm border-start border-success border-3 h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Actives</div>
                <div class="h3 fw-bold text-success mb-0">{{ $compteurs['active'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm border-start border-dark border-3 h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Expirées</div>
                <div class="h3 fw-bold text-dark mb-0">{{ $compteurs['expiree'] }}</div>
            </div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs mb-3" id="emploiTabs">
    <li class="nav-item"><a class="nav-link active" href="#" data-statut="tous">Toutes <span class="badge bg-secondary ms-1">{{ $compteurs['tous'] }}</span></a></li>
    <li class="nav-item"><a class="nav-link" href="#" data-statut="en_attente">À valider @if($compteurs['en_attente'])<span class="badge bg-warning text-dark ms-1">{{ $compteurs['en_attente'] }}</span>@endif</a></li>
    <li class="nav-item"><a class="nav-link" href="#" data-statut="active">Actives <span class="badge bg-success ms-1">{{ $compteurs['active'] }}</span></a></li>
    <li class="nav-item"><a class="nav-link" href="#" data-statut="expiree">Expirées</a></li>
    <li class="nav-item"><a class="nav-link" href="#" data-statut="rejetee">Rejetées</a></li>
</ul>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0 p-md-3">
        <table id="emploisTable" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light">
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Type</th>
                    <th>Candidatures</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Modal rejet --}}
<div class="modal fade" id="modalRejeterOffre" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Rejeter l'offre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRejeterOffre" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <p class="text-muted small mb-2" id="rejeterOffreTitre"></p>
                    <label class="form-label">Motif <span class="text-danger">*</span></label>
                    <textarea name="motif_rejet" id="motif_rejet_offre" rows="3" class="form-control" required
                              placeholder="Expliquez pourquoi l'offre est rejetée…"></textarea>
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

const table = $('#emploisTable').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
        url: '{{ route('admin.emplois.index') }}',
        data: d => { d.statut = statutActif; }
    },
    columns: [
        { data: 'titre',          name: 'titre',          responsivePriority: 1 },
        { data: 'auteur_nom',     name: 'auteur_nom',     orderable: false, responsivePriority: 4 },
        { data: 'type_badge',     name: 'type_contrat',   orderable: false, searchable: false, responsivePriority: 3 },
        { data: 'nb_candidatures',name: 'nb_candidatures',orderable: false, searchable: false, responsivePriority: 5 },
        { data: 'statut_badge',   name: 'statut',         orderable: false, searchable: false, responsivePriority: 2 },
        { data: 'created_at',     name: 'created_at',     searchable: false, responsivePriority: 5 },
        { data: 'actions',        name: 'actions',        orderable: false, searchable: false, responsivePriority: 1 },
    ],
    order: [[5, 'desc']],
    language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },
});

document.querySelectorAll('#emploiTabs .nav-link').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelectorAll('#emploiTabs .nav-link').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        statutActif = this.dataset.statut;
        table.ajax.reload();
    });
});

function validerOffre(url) {
    if (!confirm('Valider et publier cette offre ?')) return;
    const f = document.createElement('form');
    f.method = 'POST';
    f.action = url;
    f.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">'
                + '<input type="hidden" name="_method" value="PATCH">';
    document.body.appendChild(f);
    f.submit();
}

function rejeterOffre(url, titre) {
    document.getElementById('formRejeterOffre').action = url;
    document.getElementById('rejeterOffreTitre').textContent = 'Offre : ' + titre;
    document.getElementById('motif_rejet_offre').value = '';
    new bootstrap.Modal(document.getElementById('modalRejeterOffre')).show();
}
</script>
@endpush
