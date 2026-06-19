@extends('layouts.app')

@section('title', 'Modifier l\'offre')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.emplois.mes-offres') }}">Mes offres</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Modifier l'offre</h4>
    <a href="{{ route('membre.emplois.mes-offres') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

@if ($offre->statut === 'rejetee')
    <div class="alert alert-danger mb-4">
        <strong><i class="bi bi-x-circle me-1"></i>Offre rejetée :</strong> {{ $offre->motif_rejet }}
        <div class="mt-1 small">Corrigez votre offre et re-soumettez-la.</div>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('membre.emplois.update', $offre) }}">
            @csrf @method('PUT')
            @include('membre.emplois._form')
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-faaci-primary">
                    <i class="bi bi-save me-1"></i> Enregistrer et re-soumettre
                </button>
                <a href="{{ route('membre.emplois.mes-offres') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
