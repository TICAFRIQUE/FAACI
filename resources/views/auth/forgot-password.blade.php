@extends('layouts.guest')

@section('title', 'Mot de passe oublié')

@section('content')
    <h1 class="h3 fw-bold text-faaci-navy mb-1">Mot de passe oublié</h1>
    <p class="text-muted mb-4">
        Indiquez votre adresse e-mail, nous vous envoyons un lien pour réinitialiser votre mot de passe.
    </p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-faaci-navy w-100 py-2">Envoyer le lien de réinitialisation</button>
    </form>

    <p class="text-center text-muted small mt-4">
        <a href="{{ route('login') }}">Retour à la connexion</a>
    </p>
@endsection
