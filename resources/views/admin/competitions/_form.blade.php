<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
        <i class="bi bi-trophy me-1 text-faaci-steel"></i> Informations de la compétition
    </div>
    <div class="card-body pt-0">
        <div class="row g-3">
            <div class="col-12">
                <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                <input type="text" id="titre" name="titre"
                       class="form-control @error('titre') is-invalid @enderror"
                       value="{{ old('titre', $competition->titre ?? '') }}" required maxlength="200">
                @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="description" class="form-label">Description / Présentation de l'appel à projets</label>
                <textarea id="description" name="description" rows="6"
                          class="form-control @error('description') is-invalid @enderror"
                          maxlength="5000">{{ old('description', $competition->description ?? '') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-sm-6">
                <label for="budget" class="form-label">Budget alloué (FCFA)</label>
                <input type="number" id="budget" name="budget"
                       class="form-control @error('budget') is-invalid @enderror"
                       value="{{ old('budget', $competition->budget ?? '') }}" min="0" step="1000">
                @error('budget')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-sm-6">
                <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                <select id="statut" name="statut" class="form-select @error('statut') is-invalid @enderror" required>
                    @foreach (\App\Models\Competition::statuts() as $val => $lib)
                        <option value="{{ $val }}" {{ old('statut', $competition->statut ?? 'brouillon') === $val ? 'selected' : '' }}>
                            {{ $lib }}
                        </option>
                    @endforeach
                </select>
                @error('statut')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-sm-6">
                <label for="date_limite_candidature" class="form-label">Date limite de candidature</label>
                <input type="date" id="date_limite_candidature" name="date_limite_candidature"
                       class="form-control @error('date_limite_candidature') is-invalid @enderror"
                       value="{{ old('date_limite_candidature', isset($competition) && $competition->date_limite_candidature ? $competition->date_limite_candidature->format('Y-m-d') : '') }}">
                @error('date_limite_candidature')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-sm-6">
                <label for="date_pitch" class="form-label">Date du pitch / événement</label>
                <input type="date" id="date_pitch" name="date_pitch"
                       class="form-control @error('date_pitch') is-invalid @enderror"
                       value="{{ old('date_pitch', isset($competition) && $competition->date_pitch ? $competition->date_pitch->format('Y-m-d') : '') }}">
                @error('date_pitch')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>
