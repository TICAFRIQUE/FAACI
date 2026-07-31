@extends('layouts.app')

@section('title', 'Modifier mon profil')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.profil') }}">Mon profil</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Modifier mon profil</h4>
    <a href="{{ route('membre.profil') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<form method="POST" action="{{ route('membre.profil.update') }}">
    @csrf @method('PUT')

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-person me-1 text-faaci-steel"></i> Informations personnelles
                </div>
                <div class="card-body pt-0">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" id="prenom" name="prenom"
                                   class="form-control @error('prenom') is-invalid @enderror"
                                   value="{{ old('prenom', $membre->prenom) }}" required>
                            @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" id="nom" name="nom"
                                   class="form-control @error('nom') is-invalid @enderror"
                                   value="{{ old('nom', $membre->nom) }}" required>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="email" class="form-label">Adresse e-mail <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $membre->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="text" id="telephone" name="telephone"
                                   class="form-control @error('telephone') is-invalid @enderror"
                                   value="{{ old('telephone', $membre->telephone) }}"
                                   placeholder="+225 07 00 00 00 00">
                            @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="ville" class="form-label">Ville</label>
                            <select id="ville" name="ville"
                                    class="form-select @error('ville') is-invalid @enderror">
                                <option value="">— Choisir une ville —</option>
                                @foreach ($villes as $v)
                                    <option value="{{ $v }}" {{ old('ville', $membre->ville) === $v ? 'selected' : '' }}>
                                        {{ $v }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ville')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="promotion_aiesec" class="form-label">Promotion AIESEC</label>
                            <select id="promotion_aiesec" name="promotion_aiesec"
                                    class="form-select @error('promotion_aiesec') is-invalid @enderror">
                                <option value="">— Choisir une année —</option>
                                @for ($annee = $anneeMax; $annee >= $anneeMin; $annee--)
                                    <option value="{{ $annee }}"
                                        {{ (string) old('promotion_aiesec', $membre->promotion_aiesec) === (string) $annee ? 'selected' : '' }}>
                                        {{ $annee }}
                                    </option>
                                @endfor
                            </select>
                            @error('promotion_aiesec')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="comite_local" class="form-label">Comité local</label>
                            <input type="text" id="comite_local" name="comite_local"
                                   class="form-control @error('comite_local') is-invalid @enderror"
                                   value="{{ old('comite_local', $membre->comite_local) }}"
                                   placeholder="Ex : Abidjan, Bouaké…">
                            @error('comite_local')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="secteur" class="form-label">Secteur d'activité</label>
                            <select id="secteur" name="secteur"
                                    class="form-select @error('secteur') is-invalid @enderror">
                                <option value="">— Choisir un secteur —</option>
                                @foreach ($secteurs as $s)
                                    <option value="{{ $s }}" {{ old('secteur', $membre->secteur) === $s ? 'selected' : '' }}>
                                        {{ $s }}
                                    </option>
                                @endforeach
                            </select>
                            @error('secteur')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-file-text me-1 text-faaci-steel"></i> Biographie
                </div>
                <div class="card-body pt-0">
                    <x-rich-editor name="bio" toolbar="basic" :value="old('bio', $membre->bio ?? '')" :error="$errors->first('bio')" />
                    @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-tags me-1 text-faaci-steel"></i> Compétences
                </div>
                <div class="card-body pt-0" x-data="competences({{ json_encode(old('competences', $membre->competences ?? [])) }})">
                    <div class="d-flex flex-wrap gap-2 mb-2" id="tagsList">
                        <template x-for="(tag, i) in tags" :key="i">
                            <span class="badge d-inline-flex align-items-center gap-1 rounded-pill"
                                  style="background:rgba(74,127,165,0.12);color:var(--faaci-steel);font-size:0.85rem;font-weight:500;">
                                <span x-text="tag"></span>
                                <button type="button" class="btn-close btn-close-sm" style="font-size:0.5rem;" @click="remove(i)"></button>
                                <input type="hidden" name="competences[]" :value="tag">
                            </span>
                        </template>
                    </div>
                    <div class="input-group">
                        <input type="text" x-model="input" class="form-control"
                               placeholder="Ajouter une compétence puis Entrée"
                               @keydown.enter.prevent="add()" maxlength="80">
                        <button type="button" class="btn btn-outline-secondary" @click="add()">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                    <div class="form-text">Appuyez sur Entrée ou cliquez + pour ajouter. Max 80 caractères par compétence.</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Un profil complet à 100 % vous rend plus visible dans l'annuaire des membres.
                    </p>
                    <div class="mb-1 d-flex justify-content-between small">
                        <span>Complétion actuelle</span>
                        <span class="fw-semibold">{{ $membre->completeness }} %</span>
                    </div>
                    <div class="progress mb-3" style="height:6px;">
                        <div class="progress-bar" style="width:{{ $membre->completeness }}%;background:var(--faaci-steel);"></div>
                    </div>
                    <button type="submit" class="btn btn-faaci-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('membre.profil') }}" class="btn btn-outline-secondary w-100 mt-2">Annuler</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function competences(initial) {
    return {
        tags: initial || [],
        input: '',
        add() {
            const v = this.input.trim();
            if (v && !this.tags.includes(v)) this.tags.push(v);
            this.input = '';
        },
        remove(i) { this.tags.splice(i, 1); }
    };
}
document.getElementById('bio').addEventListener('input', function () {
    document.getElementById('bioCount').textContent = this.value.length + '/1000';
});
</script>
@endpush
