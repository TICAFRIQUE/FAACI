@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
    <h1 class="h3 fw-bold text-faaci-navy mb-1">Connexion</h1>
    <p class="text-muted mb-4">Accédez à votre espace membre FAACI.</p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <x-password-input id="password" name="password" autocomplete="current-password" required />
        </div>

        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small" for="remember">Se souvenir de moi</label>
            </div>
            <a href="{{ route('password.request') }}" class="small">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn btn-faaci-navy w-100 py-2">Se connecter</button>
    </form>

    <p class="text-center text-muted small mt-4">
        Pas encore membre ? <a href="{{ route('register') }}">Faire une demande d'adhésion</a>
    </p>
@endsection
