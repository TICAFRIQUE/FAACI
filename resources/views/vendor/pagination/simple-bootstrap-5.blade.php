@if ($paginator->hasPages())
<nav class="faaci-pagination-wrap" aria-label="Navigation pagination">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <p class="faaci-page-count mb-0">
            Page <span class="fw-semibold">{{ $paginator->currentPage() }}</span>
        </p>
        <div class="faaci-pagination">
            @if ($paginator->onFirstPage())
                <button class="faaci-page-btn faaci-page-btn--nav" disabled>
                    <i class="bi bi-chevron-left"></i> Précédent
                </button>
            @else
                <a class="faaci-page-btn faaci-page-btn--nav" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <i class="bi bi-chevron-left"></i> Précédent
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a class="faaci-page-btn faaci-page-btn--nav" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    Suivant <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <button class="faaci-page-btn faaci-page-btn--nav" disabled>
                    Suivant <i class="bi bi-chevron-right"></i>
                </button>
            @endif
        </div>
    </div>
</nav>
@endif
