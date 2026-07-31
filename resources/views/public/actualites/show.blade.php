@extends('layouts.public')

@section('title', $article->titre.' — FAACI')
@section('description', \Illuminate\Support\Str::limit(strip_tags($article->extrait ?: $article->contenu), 160))

@section('content')

<section class="page-header">
    <div class="container">
        <div class="page-header-breadcrumb mb-3">
            <a href="{{ route('accueil') }}">Accueil</a> / <a href="{{ route('actualites.index') }}">Actualités</a> / {{ $article->titre }}
        </div>
        <div class="d-flex align-items-center gap-3 mb-2">
            <span class="news-category">{{ $article->categorie }}</span>
            @if ($article->date_publication)
                <span class="text-white-50 small">{{ $article->date_publication->translatedFormat('j F Y') }}</span>
            @endif
        </div>
        <h1 class="page-header-title">{{ $article->titre }}</h1>
    </div>
</section>

<section class="section bg-white">
    <div class="container">
        @php $hasPhotos = $article->photos->isNotEmpty(); @endphp

        <div class="row g-5">

            {{-- Colonne gauche : contenu de l'article --}}
            <div class="{{ $hasPhotos ? 'col-lg-9' : 'col-lg-8 mx-auto' }} fade-in">
                @if ($article->image_url)
                    <img src="{{ $article->image_url }}" alt="{{ $article->titre }}"
                         class="img-fluid rounded-3 mb-4 w-100" style="max-height:400px;object-fit:cover;">
                @endif
                <div class="section-lead rich-content">
                    {!! $article->contenu !!}
                </div>
            </div>

            {{-- Colonne droite : galerie photos (uniquement si des photos existent) --}}
            @if ($hasPhotos)
                <div class="col-lg-3 fade-in">
                    <div class="sticky-top" style="top:90px;">
                        <h3 class="h5 fw-semibold mb-3">
                            <i class="bi bi-images me-2 text-faaci-steel"></i>Galerie photos
                            <span class="badge rounded-pill ms-1"
                                  style="background:rgba(74,127,165,0.12);color:var(--faaci-steel);font-size:0.75rem;">
                                {{ $article->photos->count() }}
                            </span>
                        </h3>

                        <div class="row g-2">
                            @foreach ($article->photos as $i => $photo)
                                <div class="{{ $article->photos->count() === 1 ? 'col-12' : 'col-6' }}">
                                    <a href="{{ $photo->getUrl() }}"
                                       class="glightbox d-block rounded-2 overflow-hidden"
                                       data-gallery="article-{{ $article->id }}"
                                       data-glightbox="description: {{ $article->titre }}">
                                        <img src="{{ $photo->getUrl() }}"
                                             alt="{{ $article->titre }} — photo {{ $i + 1 }}"
                                             class="img-fluid w-100"
                                             style="height:{{ $article->photos->count() === 1 ? '320px' : '110px' }};object-fit:cover;transition:transform .25s,opacity .2s;"
                                             onmouseover="this.style.transform='scale(1.04)';this.style.opacity='.9'"
                                             onmouseout="this.style.transform='scale(1)';this.style.opacity='1'">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</section>

@if ($autres->isNotEmpty())
    <section class="section bg-faaci-gray">
        <div class="container">
            <div class="text-center mb-5 fade-in">
                <span class="section-eyebrow">Vie du réseau</span>
                <h2 class="section-title">Autres actualités</h2>
            </div>
            <div class="row g-4">
                @foreach ($autres as $autre)
                    <div class="col-md-6 col-lg-4 fade-in">
                        @include('public.actualites._card', ['article' => $autre])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection

@if ($article->photos->isNotEmpty())
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
GLightbox({ selector: '.glightbox', touchNavigation: true, loop: true });
</script>
@endpush
@endif
