@php $annonce ??= null; @endphp

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Titre <span class="text-danger">*</span></label>
        <input type="text" name="titre" value="{{ old('titre', $annonce?->titre) }}"
               class="form-control @error('titre') is-invalid @enderror" required maxlength="255">
        @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        <label class="form-label">Type <span class="text-danger">*</span></label>
        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
            @foreach ($types as $val => $cfg)
                <option value="{{ $val }}" {{ old('type', $annonce?->type ?? 'info') === $val ? 'selected' : '' }}>
                    {{ $cfg['libelle'] }}
                </option>
            @endforeach
        </select>
        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        <label class="form-label">Statut <span class="text-danger">*</span></label>
        <select name="statut" class="form-select @error('statut') is-invalid @enderror" required>
            <option value="brouillon" {{ old('statut', $annonce?->statut ?? 'brouillon') === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
            <option value="publiee"   {{ old('statut', $annonce?->statut) === 'publiee'   ? 'selected' : '' }}>Publiée (visible membres)</option>
            <option value="archivee"  {{ old('statut', $annonce?->statut) === 'archivee'  ? 'selected' : '' }}>Archivée</option>
        </select>
        @error('statut') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Contenu <span class="text-danger">*</span></label>
        <x-rich-editor name="contenu" toolbar="basic"
                       :value="old('contenu', $annonce?->contenu ?? '')"
                       :error="$errors->first('contenu')" required />
    </div>

    <div class="col-md-4">
        <label class="form-label">Date d'expiration <span class="text-muted small">(optionnel)</span></label>
        <input type="datetime-local" name="expire_at"
               value="{{ old('expire_at', $annonce?->expire_at?->format('Y-m-d\TH:i')) }}"
               class="form-control @error('expire_at') is-invalid @enderror">
        <div class="form-text">Laissez vide pour afficher indéfiniment.</div>
        @error('expire_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
