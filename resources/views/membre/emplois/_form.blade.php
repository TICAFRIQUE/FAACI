@php $offre ??= null; @endphp

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Titre du poste <span class="text-danger">*</span></label>
                <input type="text" name="titre" value="{{ old('titre', $offre?->titre) }}"
                       class="form-control @error('titre') is-invalid @enderror" required>
                @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Type de contrat <span class="text-danger">*</span></label>
                <select name="type_contrat" class="form-select @error('type_contrat') is-invalid @enderror" required>
                    <option value="">— Choisir —</option>
                    @foreach (\App\Models\OffreEmploi::TYPES_CONTRAT as $val => $lib)
                        <option value="{{ $val }}" {{ old('type_contrat', $offre?->type_contrat) === $val ? 'selected' : '' }}>{{ $lib }}</option>
                    @endforeach
                </select>
                @error('type_contrat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label">Description <span class="text-danger">*</span></label>
                <x-rich-editor name="description" toolbar="full" :value="old('description', $offre?->description ?? '')" :error="$errors->first('description')" required />
            </div>

            <div class="col-md-6">
                <label class="form-label">Localisation</label>
                <input type="text" name="localisation" value="{{ old('localisation', $offre?->localisation) }}"
                       class="form-control" placeholder="Ex : Abidjan, Télétravail…">
            </div>
            <div class="col-md-6">
                <label class="form-label">Rémunération</label>
                <input type="text" name="salaire" value="{{ old('salaire', $offre?->salaire) }}"
                       class="form-control" placeholder="Ex : 300 000 FCFA / mois ou À négocier">
            </div>

            <div class="col-12">
                <label class="form-label">Compétences requises</label>
                <x-rich-editor name="competences_requises" toolbar="basic" :value="old('competences_requises', $offre?->competences_requises ?? '')" />
            </div>

            <div class="col-md-8">
                <label class="form-label">Lien externe pour postuler</label>
                <input type="url" name="lien_externe" value="{{ old('lien_externe', $offre?->lien_externe) }}"
                       class="form-control @error('lien_externe') is-invalid @enderror"
                       placeholder="https://… (si candidature via un autre site)">
                <div class="form-text">Laisser vide pour recevoir les candidatures directement sur la plateforme.</div>
                @error('lien_externe')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Date d'expiration</label>
                <input type="date" name="date_expiration"
                       value="{{ old('date_expiration', $offre?->date_expiration?->format('Y-m-d')) }}"
                       class="form-control">
            </div>
        </div>
    </div>
</div>
