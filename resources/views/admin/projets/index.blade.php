@extends('layouts.admin')

@section('title', 'Gestion des projets')
@section('page-title', 'Gestion des projets')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <p class="text-muted mb-0 small">
        Validez les projets soumis, suivez leur cycle de financement et gérez les statuts.
    </p>
</div>

{{-- KPIs rapides --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-warning border-3">
            <div class="card-body py-3">
                <div class="text-muted small mb-1">En attente</div>
                <div class="h3 fw-bold text-warning mb-0">{{ $compteurs['en_attente'] }}</div>
                <div class="small text-muted">à valider</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-primary border-3">
            <div class="card-body py-3">
                <div class="text-muted small mb-1">En financement</div>
                <div class="h3 fw-bold text-primary mb-0">{{ $compteurs['en_financement'] }}</div>
                <div class="small text-muted">actifs</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-success border-3">
            <div class="card-body py-3">
                <div class="text-muted small mb-1">Financés / En cours</div>
                <div class="h3 fw-bold text-success mb-0">{{ $compteurs['finance'] + $compteurs['en_cours'] }}</div>
                <div class="small text-muted">projets actifs</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-secondary border-3">
            <div class="card-body py-3">
                <div class="text-muted small mb-1">Total projets</div>
                <div class="h3 fw-bold text-faaci-navy mb-0">{{ $compteurs['tous'] }}</div>
                <div class="small text-muted">dont {{ $compteurs['rejete'] }} rejeté(s)</div>
            </div>
        </div>
    </div>
</div>

{{-- Tabs statuts --}}
<ul class="nav nav-tabs mb-3" id="projetsTabs">
    <li class="nav-item">
        <a class="nav-link active" href="#" data-statut="tous">
            Tous <span class="badge bg-secondary ms-1">{{ $compteurs['tous'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="en_attente">
            <i class="bi bi-hourglass-split me-1"></i>En attente
            @if ($compteurs['en_attente'] > 0)
                <span class="badge bg-warning text-dark ms-1">{{ $compteurs['en_attente'] }}</span>
            @endif
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="en_financement">
            En financement <span class="badge bg-primary ms-1">{{ $compteurs['en_financement'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="finance">
            Financé <span class="badge bg-success ms-1">{{ $compteurs['finance'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="en_cours">
            En cours <span class="badge bg-success ms-1">{{ $compteurs['en_cours'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="termine">
            Terminés <span class="badge bg-dark ms-1">{{ $compteurs['termine'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-statut="rejete">
            Rejetés <span class="badge bg-danger ms-1">{{ $compteurs['rejete'] }}</span>
        </a>
    </li>
</ul>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0 p-md-3">
        <table id="projetsTable" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light">
                <tr>
                    <th>Titre</th>
                    <th>Porteur</th>
                    <th>Cible</th>
                    <th>Collecté</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Modal rejet rapide --}}
<div class="modal fade" id="modalRejeterProjet" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Rejeter le projet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRejeterProjet" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <p class="text-muted small mb-3" id="rejeterProjetTitre"></p>
                    <label for="motif_rejet_projet" class="form-label">Motif de rejet <span class="text-danger">*</span></label>
                    <textarea id="motif_rejet_projet" name="motif_rejet" rows="3"
                              class="form-control" required
                              placeholder="Expliquez pourquoi le projet est rejeté…"></textarea>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal changement de statut --}}
<div class="modal fade" id="modalChangerStatut" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Changer le statut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formChangerStatut" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <label for="statut_select" class="form-label">Nouveau statut</label>
                    <select id="statut_select" name="statut" class="form-select">
                        <option value="en_financement">En financement</option>
                        <option value="finance">Financé</option>
                        <option value="en_cours">En cours</option>
                        <option value="termine">Terminé</option>
                    </select>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-faaci-navy">Appliquer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let statutActif = 'tous';

const table = $('#projetsTable').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
        url: '{{ route('admin.projets.index') }}',
        data: d => { d.statut = statutActif; }
    },
    columns: [
        { data: 'titre',        name: 'titre',        responsivePriority: 1 },
        { data: 'porteur',      name: 'porteur',      orderable: false,                  responsivePriority: 3 },
        { data: 'montant',      name: 'montant',      orderable: false, searchable: false, responsivePriority: 5 },
        { data: 'collecte',     name: 'collecte',     orderable: false, searchable: false, responsivePriority: 4 },
        { data: 'statut_badge', name: 'statut',       orderable: false, searchable: false, responsivePriority: 2 },
        { data: 'created_at',   name: 'created_at',   searchable: false,                 responsivePriority: 5 },
        { data: 'actions',      name: 'actions',      orderable: false, searchable: false, responsivePriority: 1 },
    ],
    order: [[5, 'desc']],
    language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },
});

document.querySelectorAll('#projetsTabs .nav-link').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelectorAll('#projetsTabs .nav-link').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        statutActif = this.dataset.statut;
        table.ajax.reload();
    });
});

function rejeterProjet(id, url, titre) {
    document.getElementById('formRejeterProjet').action = url;
    document.getElementById('motif_rejet_projet').value = '';
    document.getElementById('rejeterProjetTitre').textContent = 'Projet : ' + titre;
    new bootstrap.Modal(document.getElementById('modalRejeterProjet')).show();
}

function changerStatut(id, url, statutActuel) {
    document.getElementById('formChangerStatut').action = url;
    document.getElementById('statut_select').value = statutActuel;
    new bootstrap.Modal(document.getElementById('modalChangerStatut')).show();
}
</script>
@endpush
