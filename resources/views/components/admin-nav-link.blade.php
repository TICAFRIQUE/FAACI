@props(['route', 'icon', 'active' => null])

@php
    $isActive = request()->routeIs($active ?? $route);
    $href = \Illuminate\Support\Facades\Route::has($route) ? route($route) : '#';
@endphp

<a href="{{ $href }}" class="admin-nav-link d-flex align-items-center gap-2 {{ $isActive ? 'active' : '' }}">
    <i class="bi {{ $icon }}"></i>
    <span>{{ $slot }}</span>
</a>
