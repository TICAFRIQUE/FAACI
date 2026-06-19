@php
    $evenement ??= null;
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Titre <span class="text-danger">*</span></label>
        <input type="text" name="titre" value="{{ old('titre', $evenement?->titre) }}"
               class="form-control @error('titre') is-invalid @enderror" required>
        @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Type <span class="text-danger">*</span></label>
        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
            @foreach (\App\Models\Evenement::TYPES as $valeur => $libelle)
                <option value="{{ $valeur }}" {{ old('type', $evenement?->type) === $valeur ? 'selected' : '' }}>{{ $libelle }}</option>
            @endforeach
        </select>
        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <x-rich-editor name="description" toolbar="full" :value="old('description', $evenement?->description ?? '')" :error="$errors->first('description')" required />
    </div>

    <div class="col-md-6">
        <label class="form-label">Date et heure de début <span class="text-danger">*</span></label>
        <input type="datetime-local" name="date_debut"
               value="{{ old('date_debut', $evenement?->date_debut?->format('Y-m-d\TH:i')) }}"
               class="form-control @error('date_debut') is-invalid @enderror" required>
        @error('date_debut') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Date et heure de fin</label>
        <input type="datetime-local" name="date_fin"
               value="{{ old('date_fin', $evenement?->date_fin?->format('Y-m-d\TH:i')) }}"
               class="form-control @error('date_fin') is-invalid @enderror">
        @error('date_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Lieu</label>
        <input type="text" name="lieu" value="{{ old('lieu', $evenement?->lieu) }}"
               class="form-control @error('lieu') is-invalid @enderror" placeholder="Ex : Plateau, Abidjan">
        @error('lieu') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Lien visio (si en ligne)</label>
        <input type="text" name="lien_visio" value="{{ old('lien_visio', $evenement?->lien_visio) }}"
               class="form-control @error('lien_visio') is-invalid @enderror" placeholder="https://meet.google.com/...">
        @error('lien_visio') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Capacité maximale</label>
        <input type="number" name="capacite_max" min="1"
               value="{{ old('capacite_max', $evenement?->capacite_max) }}"
               class="form-control @error('capacite_max') is-invalid @enderror"
               placeholder="Laisser vide = illimité">
        @error('capacite_max') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 d-flex align-items-end pb-1">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="est_public" id="est_public" value="1"
                   {{ old('est_public', $evenement?->est_public) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="est_public">
                Visible sur le site public
            </label>
            <div class="form-text">Si coché, l'événement apparaît dans la section Événements du site vitrine.</div>
        </div>
    </div>

    <div class="col-md-8">
        <label class="form-label">Image</label>
        <input type="file" id="imageEvenement" name="image" accept="image/*"
               class="form-control @error('image') is-invalid @enderror">
        <div id="imageEvenementError" class="text-danger small mt-1" style="display:none"></div>
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if ($evenement?->image_url)
            <img src="{{ $evenement->image_url }}" alt="" class="img-thumbnail mt-2" style="max-height:80px;">
        @endif
    </div>
    <div class="col-md-4">
        <label class="form-label">Statut <span class="text-danger">*</span></label>
        <select name="statut" class="form-select @error('statut') is-invalid @enderror" required>
            <option value="{{ \App\Models\Evenement::STATUT_BROUILLON }}" {{ old('statut', $evenement?->statut) === \App\Models\Evenement::STATUT_BROUILLON ? 'selected' : '' }}>Brouillon</option>
            <option value="{{ \App\Models\Evenement::STATUT_PUBLIE }}" {{ old('statut', $evenement?->statut) === \App\Models\Evenement::STATUT_PUBLIE ? 'selected' : '' }}>Publié</option>
        </select>
        @error('statut') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

@push('scripts')
<script>
(function () {
    const MAX_BYTES = 1024 * 1024; // 1 Mo
    document.getElementById('imageEvenement').addEventListener('change', function () {
        const errEl = document.getElementById('imageEvenementError');
        if (this.files[0] && this.files[0].size > MAX_BYTES) {
            const taille = (this.files[0].size / (1024 * 1024)).toFixed(2) + ' Mo';
            errEl.textContent = "L'image ne doit pas dépasser 1 Mo (fichier : " + taille + ').';
            errEl.style.display = 'block';
            this.value = '';
        } else {
            errEl.style.display = 'none';
            errEl.textContent = '';
        }
    });
})();
</script>
@endpush
