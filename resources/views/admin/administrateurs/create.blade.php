@extends('layouts.admin')

@section('title', 'Nouvel administrateur')
@section('page-title', 'Nouvel administrateur')

@section('content')
    <a href="{{ route('admin.administrateurs.index') }}" class="d-inline-flex align-items-center gap-1 text-decoration-none mb-3">
        <i class="bi bi-arrow-left"></i> Retour à la liste
    </a>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm" style="max-width:600px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.administrateurs.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input id="prenom" type="text" name="prenom" value="{{ old('prenom') }}"
                               class="form-control @error('prenom') is-invalid @enderror" required autofocus>
                        @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input id="nom" type="text" name="nom" value="{{ old('nom') }}"
                               class="form-control @error('nom') is-invalid @enderror" required>
                        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone <span class="text-muted">(optionnel)</span></label>
                    <input id="telephone" type="text" name="telephone" value="{{ old('telephone') }}"
                           class="form-control @error('telephone') is-invalid @enderror">
                    @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="role" class="form-label">Rôle</label>
                    <select id="role" name="role"
                            class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">— Choisir —</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        <option value="super_admin" @selected(old('role') === 'super_admin')>Super admin</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <p class="text-muted small mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Un e-mail sera automatiquement envoyé à l'adresse indiquée avec un lien pour définir le mot de passe.
                </p>

                <button type="submit" class="btn btn-faaci-navy">Créer le compte</button>
            </form>
        </div>
    </div>
@endsection
