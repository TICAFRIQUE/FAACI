@php
    $article ??= null;
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Titre <span class="text-danger">*</span></label>
        <input type="text" name="titre" value="{{ old('titre', $article?->titre) }}"
               class="form-control @error('titre') is-invalid @enderror" required>
        @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Catégorie <span class="text-danger">*</span></label>
        <select name="categorie" class="form-select @error('categorie') is-invalid @enderror" required>
            <option value="">— Choisir une catégorie —</option>
            @foreach ([
                'Communiqué',
                'Vie du réseau',
                'Événement',
                'Partenariat',
                'Annonce',
                'Formation',
                'Témoignage',
                'International',
                'Opportunité',
                'Prix & Distinctions',
            ] as $cat)
                <option value="{{ $cat }}" {{ old('categorie', $article?->categorie) === $cat ? 'selected' : '' }}>
                    {{ $cat }}
                </option>
            @endforeach
        </select>
        @error('categorie') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-8">
        <label class="form-label">Image à la une</label>
        <input type="file" id="imageUne" name="image" accept="image/*"
               class="form-control @error('image') is-invalid @enderror">
        <div id="imageUneError" class="text-danger small mt-1" style="display:none"></div>
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if ($article?->image_url)
            <img src="{{ $article->image_url }}" alt="" class="img-thumbnail mt-2" style="max-height:80px;">
        @endif
    </div>
    <div class="col-md-4">
        <label class="form-label">Date de publication</label>
        <input type="date" name="date_publication" value="{{ old('date_publication', $article?->date_publication?->format('Y-m-d')) }}"
               class="form-control @error('date_publication') is-invalid @enderror">
        @error('date_publication') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Extrait</label>
        <x-rich-editor name="extrait" toolbar="basic" :value="old('extrait', $article?->extrait ?? '')" :error="$errors->first('extrait')" />
    </div>

    <div class="col-12">
        <label class="form-label">Contenu <span class="text-danger">*</span></label>
        <x-rich-editor name="contenu" toolbar="full" :value="old('contenu', $article?->contenu ?? '')" :error="$errors->first('contenu')" required />
    </div>

    <div class="col-md-4">
        <label class="form-label">Statut <span class="text-danger">*</span></label>
        <select name="statut" class="form-select @error('statut') is-invalid @enderror" required>
            <option value="{{ \App\Models\Article::STATUT_BROUILLON }}" {{ old('statut', $article?->statut) === \App\Models\Article::STATUT_BROUILLON ? 'selected' : '' }}>Brouillon</option>
            <option value="{{ \App\Models\Article::STATUT_PUBLIE }}" {{ old('statut', $article?->statut) === \App\Models\Article::STATUT_PUBLIE ? 'selected' : '' }}>Publié</option>
        </select>
        @error('statut') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- ── Galerie photos ── --}}
    <div class="col-12">
        <hr class="my-1">
        <label class="form-label fw-semibold">Galerie photos</label>
        <div class="form-text mb-2">
            Sélectionnez une ou plusieurs photos. Vous pouvez en ajouter à plusieurs reprises — elles s'accumulent.
            Cliquez sur <i class="bi bi-x-circle-fill text-danger"></i> pour retirer une photo avant d'enregistrer.
        </div>

        {{-- Photos existantes (édition) --}}
        @if ($article && $article->photos->isNotEmpty())
            <p class="small fw-semibold text-muted mb-1">Photos enregistrées</p>
            <div class="row g-2 mb-3" id="existingPhotosGrid">
                @foreach ($article->photos as $photo)
                    <div class="col-6 col-md-3 col-lg-2" id="photo-{{ $photo->id }}">
                        <div class="position-relative">
                            <img src="{{ $photo->getUrl() }}" alt=""
                                 class="img-thumbnail w-100" style="height:100px;object-fit:cover;">
                            <button type="button"
                                    class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-1 btn-delete-photo"
                                    data-url="{{ route('admin.articles.photos.destroy', [$article, $photo]) }}"
                                    data-photo-id="{{ $photo->id }}"
                                    title="Supprimer"
                                    style="width:26px;height:26px;padding:0;font-size:0.75rem;">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Upload nouvelles photos --}}
        @if ($article && $article->photos->isNotEmpty())
            <p class="small fw-semibold text-muted mb-1">Ajouter de nouvelles photos</p>
        @endif
        <input type="file" id="photosInput" name="photos[]" multiple accept="image/*"
               class="form-control @error('photos.*') is-invalid @enderror">
        <div id="photosError" class="text-danger small mt-1" style="display:none"></div>
        @error('photos.*') <div class="invalid-feedback">{{ $message }}</div> @enderror

        {{-- Aperçu des nouvelles photos à enregistrer --}}
        <div class="row g-2 mt-2" id="photoPreviewGrid"></div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const MAX_BYTES = 1024 * 1024; // 1 Mo

    /* ── Image à la une ── */
    document.getElementById('imageUne').addEventListener('change', function () {
        const errEl = document.getElementById('imageUneError');
        if (this.files[0] && this.files[0].size > MAX_BYTES) {
            errEl.textContent = "L'image à la une ne doit pas dépasser 1 Mo (fichier : " + formatSize(this.files[0].size) + ').';
            errEl.style.display = 'block';
            this.value = '';
        } else {
            errEl.style.display = 'none';
            errEl.textContent = '';
        }
    });

    /* ── Galerie photos ── */
    let fileQueue = new DataTransfer();

    const input    = document.getElementById('photosInput');
    const grid     = document.getElementById('photoPreviewGrid');
    const photosErr = document.getElementById('photosError');

    input.addEventListener('change', function () {
        const refused = [];
        Array.from(this.files).forEach(f => {
            if (f.size > MAX_BYTES) {
                refused.push(f.name + ' (' + formatSize(f.size) + ')');
            } else {
                fileQueue.items.add(f);
            }
        });
        input.files = fileQueue.files;

        if (refused.length) {
            photosErr.textContent = 'Photo(s) rejetée(s) — dépasse 1 Mo : ' + refused.join(', ');
            photosErr.style.display = 'block';
        } else {
            photosErr.style.display = 'none';
            photosErr.textContent = '';
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
                    + '<img src="' + e.target.result + '" class="img-thumbnail w-100" style="height:100px;object-fit:cover;" alt="">'
                    + '<span class="position-absolute top-0 start-0 m-1 badge bg-success" style="font-size:0.6rem;">Nouveau</span>'
                    + '<button type="button" onclick="window.__removePhoto(' + idx + ')"'
                    + ' class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-1"'
                    + ' title="Retirer" style="width:26px;height:26px;padding:0;font-size:0.75rem;">'
                    + '<i class="bi bi-x"></i></button>'
                    + '</div>';
                grid.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }

    function formatSize(bytes) {
        return (bytes / (1024 * 1024)).toFixed(2) + ' Mo';
    }

    window.__removePhoto = removeQueued;
})();

/* ── Suppression des photos existantes via fetch (pas de form imbriqué) ── */
document.querySelectorAll('.btn-delete-photo').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const url     = this.dataset.url;
        const photoId = this.dataset.photoId;

        const modalEl = document.getElementById('faacConfirmModal');
        const modal   = bootstrap.Modal.getOrCreateInstance(modalEl);
        const msgEl   = document.getElementById('faacConfirmMessage');
        const okBtn   = document.getElementById('faacConfirmOk');

        msgEl.textContent = 'Supprimer définitivement cette photo ?';

        function onConfirm() {
            okBtn.removeEventListener('click', onConfirm);
            modal.hide();

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: new URLSearchParams({ _method: 'DELETE' }),
            })
            .then(function (res) {
                if (res.ok) {
                    const el = document.getElementById('photo-' + photoId);
                    if (el) el.remove();
                } else {
                    console.error('Suppression échouée', res.status);
                }
            })
            .catch(function (err) { console.error('Erreur réseau', err); });
        }

        // Nettoyer les anciens listeners avant d'en ajouter un nouveau
        const freshOkBtn = okBtn.cloneNode(true);
        okBtn.parentNode.replaceChild(freshOkBtn, okBtn);
        freshOkBtn.addEventListener('click', onConfirm);

        modal.show();
    });
});
</script>
@endpush
