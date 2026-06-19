@php
    $slide ??= null;
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Titre <span class="text-danger">*</span></label>
        <input type="text" name="titre" value="{{ old('titre', $slide?->titre) }}"
               class="form-control @error('titre') is-invalid @enderror" required>
        @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Image de fond</label>
        <input type="file" id="imageSlide" name="image" accept="image/*"
               class="form-control @error('image') is-invalid @enderror">
        <div id="imageSlideError" class="text-danger small mt-1" style="display:none"></div>
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if ($slide?->image_url)
            <img src="{{ $slide->image_url }}" alt="" class="img-thumbnail mt-2" style="max-height:80px;">
        @endif
    </div>

    <div class="col-12">
        <label class="form-label">Sous-titre</label>
        <input type="text" name="sous_titre" value="{{ old('sous_titre', $slide?->sous_titre) }}"
               class="form-control @error('sous_titre') is-invalid @enderror">
        @error('sous_titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $slide?->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Bouton 1 — libellé</label>
        <input type="text" name="libelle_bouton_1" value="{{ old('libelle_bouton_1', $slide?->libelle_bouton_1) }}"
               class="form-control @error('libelle_bouton_1') is-invalid @enderror" placeholder="Ex : Devenir membre">
        @error('libelle_bouton_1') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Bouton 1 — lien</label>
        <input type="text" name="lien_bouton_1" value="{{ old('lien_bouton_1', $slide?->lien_bouton_1) }}"
               class="form-control @error('lien_bouton_1') is-invalid @enderror" placeholder="/inscription">
        @error('lien_bouton_1') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Bouton 2 — libellé</label>
        <input type="text" name="libelle_bouton_2" value="{{ old('libelle_bouton_2', $slide?->libelle_bouton_2) }}"
               class="form-control @error('libelle_bouton_2') is-invalid @enderror" placeholder="Ex : En savoir plus">
        @error('libelle_bouton_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Bouton 2 — lien</label>
        <input type="text" name="lien_bouton_2" value="{{ old('lien_bouton_2', $slide?->lien_bouton_2) }}"
               class="form-control @error('lien_bouton_2') is-invalid @enderror" placeholder="#about">
        @error('lien_bouton_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input type="checkbox" name="actif" id="actif" class="form-check-input" value="1"
                   {{ old('actif', $slide?->actif ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="actif">Slide active (visible sur le site)</label>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const MAX_BYTES = 1024 * 1024;
    document.getElementById('imageSlide').addEventListener('change', function () {
        const errEl = document.getElementById('imageSlideError');
        if (this.files[0] && this.files[0].size > MAX_BYTES) {
            const taille = (this.files[0].size / (1024 * 1024)).toFixed(2) + ' Mo';
            errEl.textContent = "L'image de fond ne doit pas dépasser 1 Mo (fichier : " + taille + ').';
            errEl.style.display = 'block';
            this.value = '';
        } else {
            errEl.style.display = 'none';
        }
    });
})();
</script>
@endpush
