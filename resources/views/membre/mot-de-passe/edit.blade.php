@extends('layouts.app')

@section('title', 'Modifier mon mot de passe')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mot de passe</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-bold mb-0">Modifier mon mot de passe</h4>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('membre.mot-de-passe.update') }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel <span class="text-danger">*</span></label>
                        <x-password-input id="current_password" name="current_password" autocomplete="current-password" />
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe <span class="text-danger">*</span></label>
                        <x-password-input id="password" name="password" autocomplete="new-password" />
                        <div class="form-text">Au moins 8 caractères.</div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe <span class="text-danger">*</span></label>
                        <x-password-input id="password_confirmation" name="password_confirmation" autocomplete="new-password" />
                    </div>

                    <button type="submit" class="btn btn-faaci-primary w-100">
                        <i class="bi bi-lock me-1"></i> Enregistrer le nouveau mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
