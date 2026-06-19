@php $activite ??= null; @endphp

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Icône <span class="text-danger">*</span></label>
        <input type="text" name="icone" value="{{ old('icone', $activite?->icone ?? 'bi-star') }}"
               class="form-control @error('icone') is-invalid @enderror" required>
        <div class="form-text">Classe Bootstrap Icons, ex : <code>bi-briefcase</code>, <code>bi-people</code>.</div>
        @error('icone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8">
        <label class="form-label">Titre <span class="text-danger">*</span></label>
        <input type="text" name="titre" value="{{ old('titre', $activite?->titre) }}"
               class="form-control @error('titre') is-invalid @enderror" required>
        @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <x-rich-editor name="description" toolbar="basic" :value="old('description', $activite?->description ?? '')" :error="$errors->first('description')" />
    </div>
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="actif" name="actif" value="1"
                   {{ old('actif', $activite?->actif ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="actif">Visible sur le site</label>
        </div>
    </div>
</div>
