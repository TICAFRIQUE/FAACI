@extends('layouts.guest')

@section('title', 'Demande d\'adhésion')

@section('content')
    <h1 class="h3 fw-bold text-faaci-navy mb-1">Demande d'adhésion</h1>
    <p class="text-muted mb-4">
        Rejoignez le réseau des Alumni AIESEC Côte d'Ivoire. Votre compte sera activé
        après validation par un administrateur.
    </p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input id="prenom" type="text" name="prenom" value="{{ old('prenom') }}"
                       class="form-control @error('prenom') is-invalid @enderror" required autofocus>
                @error('prenom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input id="nom" type="text" name="nom" value="{{ old('nom') }}"
                       class="form-control @error('nom') is-invalid @enderror" required>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone <span class="text-muted">(optionnel)</span></label>
            <input id="telephone" type="text" name="telephone" value="{{ old('telephone') }}"
                   class="form-control @error('telephone') is-invalid @enderror">
            @error('telephone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <x-password-input id="password" name="password" autocomplete="new-password" required />
            </div>

            <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <x-password-input id="password_confirmation" name="password_confirmation" autocomplete="new-password" required />
            </div>
        </div>

        <button type="submit" class="btn btn-faaci-navy w-100 py-2 mt-2">Envoyer ma demande</button>
    </form>

    <p class="text-center text-muted small mt-4">
        Déjà membre ? <a href="{{ route('login') }}">Se connecter</a>
    </p>
@endsection
