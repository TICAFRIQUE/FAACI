@extends('layouts.admin')

@section('title', "Modifier l'album")
@section('page-title', "Modifier l'album")

@section('content')
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.galerie.update', $album) }}">
                @csrf
                @method('PUT')
                @include('admin.galerie._form')

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-faaci-navy">Enregistrer</button>
                    <a href="{{ route('admin.galerie.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h6 class="fw-semibold mb-3">Ajouter des photos</h6>
            <form method="POST" action="{{ route('admin.galerie.images.store', $album) }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-9">
                        <input type="file" id="galerieImages" name="images[]" accept="image/*" multiple
                               class="form-control @error('images') is-invalid @enderror">
                        <div id="galerieImagesError" class="text-danger small mt-1" style="display:none"></div>
                        @error('images') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @error('images.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <div class="row g-2 mt-1" id="galeriePreviewGrid"></div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-faaci-navy w-100">
                            <i class="bi bi-upload me-1"></i> Ajouter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h6 class="fw-semibold mb-3">Photos de l'album ({{ $album->images->count() }})</h6>

            @if ($album->images->isEmpty())
                <p class="text-muted mb-0">Aucune photo dans cet album pour le moment.</p>
            @else
                <div class="row g-3">
                    @foreach ($album->images as $image)
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="border rounded overflow-hidden">
                                <div style="aspect-ratio: 4/3; background: var(--faaci-gray);">
                                    @if ($image->image_url)
                                        <img src="{{ $image->image_url }}" alt="" class="w-100 h-100" style="object-fit:cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="bi bi-image text-muted fs-3"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center p-1">
                                    <div class="d-flex gap-1">
                                        <form method="POST" action="{{ route('admin.galerie.images.deplacer', [$album, $image]) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="direction" value="haut">
                                            <button class="btn btn-sm btn-light py-0 px-1" @if ($loop->first) disabled @endif title="Déplacer avant">
                                                <i class="bi bi-arrow-left"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.galerie.images.deplacer', [$album, $image]) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="direction" value="bas">
                                            <button class="btn btn-sm btn-light py-0 px-1" @if ($loop->last) disabled @endif title="Déplacer après">
                                                <i class="bi bi-arrow-right"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <form method="POST" action="{{ route('admin.galerie.images.destroy', [$album, $image]) }}"
                                          onsubmit="return confirm('Supprimer cette photo ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    const MAX_BYTES = 1024 * 1024;
    let fileQueue = new DataTransfer();

    const input   = document.getElementById('galerieImages');
    const errEl   = document.getElementById('galerieImagesError');
    const grid    = document.getElementById('galeriePreviewGrid');

    input.addEventListener('change', function () {
        const refused = [];
        Array.from(this.files).forEach(f => {
            if (f.size > MAX_BYTES) {
                refused.push(f.name + ' (' + (f.size / (1024 * 1024)).toFixed(2) + ' Mo)');
            } else {
                fileQueue.items.add(f);
            }
        });
        input.files = fileQueue.files;

        if (refused.length) {
            errEl.textContent = 'Photo(s) rejetée(s) — dépasse 1 Mo : ' + refused.join(', ');
            errEl.style.display = 'block';
        } else {
            errEl.style.display = 'none';
        }

        renderPreviews();
    });

    function removeQueued(index) {
        const fresh = new DataTransfer();
        Array.from(fileQueue.files).forEach((f, i) => { if (i !== index) fresh.items.add(f); });
        fileQueue = fresh;
        input.files = fileQueue.files;
        renderPreviews();
    }

    function renderPreviews() {
        grid.innerHTML = '';
        Array.from(fileQueue.files).forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = e => {
                const col = document.createElement('div');
                col.className = 'col-6 col-md-3 col-lg-2';
                col.innerHTML =
                    '<div class="position-relative">'
                    + '<img src="' + e.target.result + '" class="img-thumbnail w-100" style="height:80px;object-fit:cover;" alt="">'
                    + '<button type="button" onclick="window.__removeGaleriePhoto(' + idx + ')"'
                    + ' class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-1"'
                    + ' title="Retirer" style="width:22px;height:22px;padding:0;font-size:0.65rem;">'
                    + '<i class="bi bi-x"></i></button>'
                    + '</div>';
                grid.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }

    window.__removeGaleriePhoto = removeQueued;
})();
</script>
@endpush
