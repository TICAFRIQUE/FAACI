@props(['dark' => false])

@php
    $parametres = \App\Models\ParametreSite::tous();
    $logoUrl    = !empty($parametres['logo']) ? $parametres['logo'] : asset('images/logo.jpg');
@endphp

<div class="d-flex align-items-center gap-2">
    <img src="{{ $logoUrl }}" alt="Logo FAACI"
         class="logo-svg rounded-circle" style="object-fit:cover;">
    <div>
        <div class="logo-text {{ $dark ? 'text-faaci-navy' : '' }}">FAACI</div>
        <div class="logo-sub {{ $dark ? 'text-faaci-steel' : '' }}">Fondation Alumni AIESEC CI</div>
    </div>
</div>
