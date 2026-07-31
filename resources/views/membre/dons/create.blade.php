@extends('layouts.app')

@section('title', 'Faire un don')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.dons.index') }}">Mes dons</a></li>
    <li class="breadcrumb-item active">Nouveau don</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Faire un don à la fondation</h4>
    <a href="{{ route('membre.dons.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('membre.dons.store') }}" enctype="multipart/form-data"
              x-data="donForm()">
            @csrf

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-gift me-1 text-faaci-steel"></i> Nature du don
                </div>
                <div class="card-body pt-0">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Type de don <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach (\App\Models\Don::natures() as $val => $lib)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="nature"
                                               id="nature_{{ $val }}" value="{{ $val }}"
                                               x-model="nature"
                                               {{ old('nature', 'argent') === $val ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="nature_{{ $val }}">{{ $lib }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('nature')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label for="libelle" class="form-label">Intitulé du don <span class="text-danger">*</span></label>
                            <input type="text" id="libelle" name="libelle"
                                   class="form-control @error('libelle') is-invalid @enderror"
                                   value="{{ old('libelle') }}"
                                   placeholder="Ex : Don pour l'événement annuel">
                            @error('libelle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Champs spécifiques argent --}}
                        <template x-if="nature === 'argent'">
                            <div class="col-12">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label for="montant" class="form-label">Montant (FCFA) <span class="text-danger">*</span></label>
                                        <input type="number" id="montant" name="montant"
                                               class="form-control @error('montant') is-invalid @enderror"
                                               value="{{ old('montant') }}" min="100" step="100"
                                               placeholder="Ex : 50000">
                                        @error('montant')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="moyen_paiement" class="form-label">Moyen de paiement <span class="text-danger">*</span></label>
                                        <select id="moyen_paiement" name="moyen_paiement"
                                                class="form-select @error('moyen_paiement') is-invalid @enderror">
                                            <option value="">— Choisir —</option>
                                            @foreach (\App\Models\Don::moyensPaiement() as $k => $v)
                                                <option value="{{ $k }}" {{ old('moyen_paiement') === $k ? 'selected' : '' }}>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                        @error('moyen_paiement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Champs matériel / autre --}}
                        <template x-if="nature !== 'argent'">
                            <div class="col-sm-6">
                                <label for="valeur_estimee" class="form-label">Valeur estimée (optionnel)</label>
                                <input type="text" id="valeur_estimee" name="valeur_estimee"
                                       class="form-control @error('valeur_estimee') is-invalid @enderror"
                                       value="{{ old('valeur_estimee') }}"
                                       placeholder="Ex : 3 ordinateurs portables, ~150 000 FCFA">
                                @error('valeur_estimee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </template>

                        <div class="col-12">
                            <label for="description" class="form-label">Description / Précisions</label>
                            <textarea id="description" name="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Décrivez votre don, ses conditions, sa destination souhaitée…">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label for="preuve" class="form-label">Preuve / Justificatif (optionnel)</label>
                            <input type="file" id="preuve" name="preuve"
                                   class="form-control @error('preuve') is-invalid @enderror"
                                   accept="image/*,.pdf">
                            <div class="form-text">Photo, reçu ou document justificatif. Max 2 Mo.</div>
                            @error('preuve')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex gap-2 justify-content-end">
                    <a href="{{ route('membre.dons.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-faaci-primary">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer le don
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function donForm() {
    return {
        nature: '{{ old('nature', 'argent') }}'
    };
}
</script>
@endpush
