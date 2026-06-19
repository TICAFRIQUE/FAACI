@php $offre ??= null; @endphp

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
        <label class="form-label">Description du poste <span class="text-danger">*</span></label>
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
               class="form-control" placeholder="Ex : 400 000 – 600 000 FCFA / mois">
    </div>

    <div class="col-12">
        <label class="form-label">Compétences requises</label>
        <x-rich-editor name="competences_requises" toolbar="basic" :value="old('competences_requises', $offre?->competences_requises ?? '')" placeholder="Liste des compétences attendues…" />
    </div>

    <div class="col-md-8">
        <label class="form-label">Lien de candidature externe</label>
        <input type="url" name="lien_externe" value="{{ old('lien_externe', $offre?->lien_externe) }}"
               class="form-control @error('lien_externe') is-invalid @enderror"
               placeholder="https://… (si candidature hors plateforme)">
        <div class="form-text">Si renseigné, les membres seront redirigés vers ce lien.</div>
        @error('lien_externe')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Date d'expiration</label>
        <input type="date" name="date_expiration"
               value="{{ old('date_expiration', $offre?->date_expiration?->format('Y-m-d')) }}"
               class="form-control @error('date_expiration') is-invalid @enderror">
        @error('date_expiration')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
