@php
    $album ??= null;
@endphp

<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Titre <span class="text-danger">*</span></label>
        <input type="text" name="titre" value="{{ old('titre', $album?->titre) }}"
               class="form-control @error('titre') is-invalid @enderror" required>
        @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $album?->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input type="checkbox" name="actif" id="actif" class="form-check-input" value="1"
                   {{ old('actif', $album?->actif ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="actif">Album actif (visible sur le site)</label>
        </div>
    </div>
</div>
