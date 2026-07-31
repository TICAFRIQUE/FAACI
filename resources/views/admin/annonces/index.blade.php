@extends('layouts.admin')
@section('title', 'Annonces membres')
@section('page-title', 'Annonces membres')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Messages et informations envoyés à tous les membres actifs.</p>
    <a href="{{ route('admin.annonces.create') }}" class="btn btn-faaci-navy">
        <i class="bi bi-plus-lg me-1"></i> Nouvelle annonce
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0 p-md-3">
        <table id="annoncesTable" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light">
                <tr>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Publiée le</th>
                    <th>Expire le</th>
                    <th>Auteur</th>
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
    $('#annoncesTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route('admin.annonces.index') }}',
        columns: [
            { data: 'titre',       responsivePriority: 1 },
            { data: 'type_badge',  orderable: false, searchable: false, responsivePriority: 3 },
            { data: 'statut_badge',orderable: false, searchable: false, responsivePriority: 2 },
            { data: 'publiee_fmt', searchable: false,                   responsivePriority: 4 },
            { data: 'expire_fmt',  searchable: false,                   responsivePriority: 5 },
            { data: 'auteur_nom',  orderable: false, searchable: false,  responsivePriority: 6 },
            { data: 'actions',     orderable: false, searchable: false,  responsivePriority: 1, className: 'text-end' },
        ],
        order: [[0, 'desc']],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },
    });
});
</script>
@endpush
