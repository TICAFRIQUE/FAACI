@php $type ??= null; @endphp

<div class="mb-3">
    <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
    <input type="text" name="nom" value="{{ old('nom', $type?->nom) }}"
           class="form-control @error('nom') is-invalid @enderror" required maxlength="200"
           placeholder="Ex : Cotisation mensuelle membres">
    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row g-3">
    <div class="col-sm-6">
        <label class="form-label fw-semibold">Fréquence <span class="text-danger">*</span></label>
        <select name="frequence" class="form-select @error('frequence') is-invalid @enderror" required>
            <option value="">— Choisir —</option>
            @foreach (\App\Models\TypeCotisation::frequences() as $val => $lib)
                <option value="{{ $val }}" {{ old('frequence', $type?->frequence) === $val ? 'selected' : '' }}>
                    {{ $lib }}
                </option>
            @endforeach
        </select>
        @error('frequence')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6">
        <label class="form-label fw-semibold">Montant standard (FCFA) <span class="text-danger">*</span></label>
        <input type="number" name="montant_standard" min="0" step="100"
               value="{{ old('montant_standard', $type?->montant_standard) }}"
               class="form-control @error('montant_standard') is-invalid @enderror" required>
        @error('montant_standard')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3 mt-3">
    <label class="form-label fw-semibold">Description</label>
    <textarea name="description" rows="3"
              class="form-control @error('description') is-invalid @enderror"
              maxlength="500">{{ old('description', $type?->description) }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row g-3 mt-0 mb-3">
    <div class="col-sm-6">
        <label class="form-label fw-semibold">Date de début <span class="text-danger">*</span></label>
        <input type="date" name="date_debut"
               value="{{ old('date_debut', $type?->date_debut?->format('Y-m-d')) }}"
               class="form-control @error('date_debut') is-invalid @enderror" required>
        <div class="form-text">Première période de cotisation.</div>
        @error('date_debut')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6">
        <label class="form-label fw-semibold">Date de fin</label>
        <input type="date" name="date_fin"
               value="{{ old('date_fin', $type?->date_fin?->format('Y-m-d')) }}"
               class="form-control @error('date_fin') is-invalid @enderror">
        <div class="form-text">Laisser vide = sans fin (indéfini).</div>
        @error('date_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-check mt-2">
    <input type="hidden" name="actif" value="0">
    <input type="checkbox" name="actif" value="1" id="actif" class="form-check-input"
           {{ old('actif', $type?->actif ?? true) ? 'checked' : '' }}>
    <label for="actif" class="form-check-label">Type actif (visible dans l'espace membre)</label>
</div>
