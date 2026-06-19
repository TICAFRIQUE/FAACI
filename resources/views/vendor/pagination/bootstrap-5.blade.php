@if ($paginator->hasPages())
<nav class="faaci-pagination-wrap" aria-label="Navigation pagination">

    {{-- Mobile : prev / next seulement --}}
    <div class="d-flex align-items-center justify-content-between d-sm-none gap-2">
        @if ($paginator->onFirstPage())
            <button class="faaci-page-btn faaci-page-btn--nav" disabled>
                <i class="bi bi-arrow-left"></i> Précédent
            </button>
        @else
            <a class="faaci-page-btn faaci-page-btn--nav" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                <i class="bi bi-arrow-left"></i> Précédent
            </a>
        @endif

        <span class="faaci-page-info">
            {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
        </span>

        @if ($paginator->hasMorePages())
            <a class="faaci-page-btn faaci-page-btn--nav" href="{{ $paginator->nextPageUrl() }}" rel="next">
                Suivant <i class="bi bi-arrow-right"></i>
            </a>
        @else
            <button class="faaci-page-btn faaci-page-btn--nav" disabled>
                Suivant <i class="bi bi-arrow-right"></i>
            </button>
        @endif
    </div>

    {{-- Desktop : pagination complète --}}
    <div class="d-none d-sm-flex align-items-center justify-content-between gap-3">

        {{-- Compteur --}}
        <p class="faaci-page-count mb-0">
            <span>{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
            sur <span class="fw-semibold">{{ $paginator->total() }}</span> résultat{{ $paginator->total() > 1 ? 's' : '' }}
        </p>

        {{-- Boutons --}}
        <div class="faaci-pagination">

            {{-- Précédent --}}
            @if ($paginator->onFirstPage())
                <button class="faaci-page-btn faaci-page-btn--nav" disabled aria-label="Page précédente">
                    <i class="bi bi-chevron-left"></i>
                </button>
            @else
                <a class="faaci-page-btn faaci-page-btn--nav" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Page précédente">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @endif

            {{-- Numéros --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="faaci-page-dots">…</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="faaci-page-btn faaci-page-btn--active" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="faaci-page-btn" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Suivant --}}
            @if ($paginator->hasMorePages())
                <a class="faaci-page-btn faaci-page-btn--nav" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Page suivante">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <button class="faaci-page-btn faaci-page-btn--nav" disabled aria-label="Page suivante">
                    <i class="bi bi-chevron-right"></i>
                </button>
            @endif
        </div>
    </div>
</nav>
@endif
