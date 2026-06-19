@extends('layouts.app')

@section('title', 'Publier une offre')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.emplois.index') }}">Emplois</a></li>
    <li class="breadcrumb-item"><a href="{{ route('membre.emplois.mes-offres') }}">Mes offres</a></li>
    <li class="breadcrumb-item active">Publier</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Publier une offre d'emploi</h4>
    <a href="{{ route('membre.emplois.mes-offres') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="alert alert-info small mb-4">
    <i class="bi bi-info-circle me-1"></i>
    Votre offre sera soumise à validation avant d'être publiée auprès des membres.
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('membre.emplois.store') }}">
            @csrf
            @include('membre.emplois._form')
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-faaci-primary">
                    <i class="bi bi-send me-1"></i> Soumettre à validation
                </button>
                <a href="{{ route('membre.emplois.mes-offres') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
