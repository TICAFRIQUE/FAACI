@extends('layouts.guest')

@section('title', 'Réinitialiser le mot de passe')

@section('content')
    <h1 class="h3 fw-bold text-faaci-navy mb-1">Réinitialiser le mot de passe</h1>
    <p class="text-muted mb-4">Choisissez un nouveau mot de passe pour votre compte.</p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email) }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Nouveau mot de passe</label>
            <x-password-input id="password" name="password" autocomplete="new-password" required />
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <x-password-input id="password_confirmation" name="password_confirmation" autocomplete="new-password" required />
        </div>

        <button type="submit" class="btn btn-faaci-navy w-100 py-2">Réinitialiser le mot de passe</button>
    </form>
@endsection
