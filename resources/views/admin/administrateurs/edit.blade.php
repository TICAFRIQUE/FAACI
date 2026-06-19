@extends('layouts.admin')

@section('title', 'Modifier — '.$utilisateur->nom_complet)
@section('page-title', 'Modifier un administrateur')

@section('content')
    <a href="{{ route('admin.administrateurs.show', $utilisateur) }}" class="d-inline-flex align-items-center gap-1 text-decoration-none mb-3">
        <i class="bi bi-arrow-left"></i> Retour au profil
    </a>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm" style="max-width:600px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.administrateurs.update', $utilisateur) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input id="prenom" type="text" name="prenom"
                               value="{{ old('prenom', $utilisateur->prenom) }}"
                               class="form-control @error('prenom') is-invalid @enderror" required autofocus>
                        @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input id="nom" type="text" name="nom"
                               value="{{ old('nom', $utilisateur->nom) }}"
                               class="form-control @error('nom') is-invalid @enderror" required>
                        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email', $utilisateur->email) }}"
                           class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="telephone" class="form-label">Téléphone <span class="text-muted">(optionnel)</span></label>
                    <input id="telephone" type="text" name="telephone"
                           value="{{ old('telephone', $utilisateur->telephone) }}"
                           class="form-control @error('telephone') is-invalid @enderror">
                    @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-faaci-navy">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
@endsection
