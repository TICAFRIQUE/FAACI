@php
    $valeur ??= null;
@endphp

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Icône <span class="text-danger">*</span></label>
        <input type="text" name="icone" value="{{ old('icone', $valeur?->icone ?? 'bi-star') }}"
               class="form-control @error('icone') is-invalid @enderror" required>
        <div class="form-text">Classe Bootstrap Icons, ex : <code>bi-people</code>, <code>bi-shield-check</code>.</div>
        @error('icone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-8">
        <label class="form-label">Titre <span class="text-danger">*</span></label>
        <input type="text" name="titre" value="{{ old('titre', $valeur?->titre) }}"
               class="form-control @error('titre') is-invalid @enderror" required>
        @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <x-rich-editor name="description" toolbar="basic" :value="old('description', $valeur?->description ?? '')" :error="$errors->first('description')" />
    </div>
</div>
