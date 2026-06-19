@php
    $membre ??= null;
@endphp

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Prénom <span class="text-danger">*</span></label>
        <input type="text" name="prenom" value="{{ old('prenom', $membre?->prenom) }}"
               class="form-control @error('prenom') is-invalid @enderror" required>
        @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Nom <span class="text-danger">*</span></label>
        <input type="text" name="nom" value="{{ old('nom', $membre?->nom) }}"
               class="form-control @error('nom') is-invalid @enderror" required>
        @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Photo</label>
        <input type="file" id="photoMembre" name="photo" accept="image/*"
               class="form-control @error('photo') is-invalid @enderror">
        <div id="photoMembreError" class="text-danger small mt-1" style="display:none"></div>
        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if ($membre?->photo_url)
            <img src="{{ $membre->photo_url }}" alt="" class="img-thumbnail mt-2" style="max-height:80px;">
        @endif
    </div>

    <div class="col-12">
        <label class="form-label">Fonction <span class="text-danger">*</span></label>
        <input type="text" name="fonction" value="{{ old('fonction', $membre?->fonction) }}"
               class="form-control @error('fonction') is-invalid @enderror" placeholder="Ex : Présidente de la FAACI" required>
        @error('fonction') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Bio</label>
        <x-rich-editor name="bio" toolbar="basic" :value="old('bio', $membre?->bio ?? '')" :error="$errors->first('bio')" />
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input type="checkbox" name="actif" id="actif" class="form-check-input" value="1"
                   {{ old('actif', $membre?->actif ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="actif">Membre visible sur le site</label>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const MAX_BYTES = 1024 * 1024;
    document.getElementById('photoMembre').addEventListener('change', function () {
        const errEl = document.getElementById('photoMembreError');
        if (this.files[0] && this.files[0].size > MAX_BYTES) {
            const taille = (this.files[0].size / (1024 * 1024)).toFixed(2) + ' Mo';
            errEl.textContent = 'La photo ne doit pas dépasser 1 Mo (fichier : ' + taille + ').';
            errEl.style.display = 'block';
            this.value = '';
        } else {
            errEl.style.display = 'none';
        }
    });
})();
</script>
@endpush
