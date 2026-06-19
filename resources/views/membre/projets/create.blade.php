@extends('layouts.app')

@section('title', 'Soumettre un projet')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.projets.index') }}">Projets</a></li>
    <li class="breadcrumb-item active">Nouveau projet</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Soumettre un projet</h4>
    <a href="{{ route('membre.projets.mes-projets') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Mes projets
    </a>
</div>

<form method="POST" action="{{ route('membre.projets.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-lightbulb me-1 text-faaci-steel"></i> Informations du projet
                </div>
                <div class="card-body pt-0">
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre du projet <span class="text-danger">*</span></label>
                        <input type="text" id="titre" name="titre"
                               class="form-control @error('titre') is-invalid @enderror"
                               value="{{ old('titre') }}" placeholder="Un titre clair et accrocheur" required>
                        @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description_courte" class="form-label">Résumé court</label>
                        <input type="text" id="description_courte" name="description_courte"
                               class="form-control @error('description_courte') is-invalid @enderror"
                               value="{{ old('description_courte') }}"
                               placeholder="En une phrase, de quoi s'agit-il ?" maxlength="300">
                        <div class="form-text">Affiché dans la liste des projets. Max 300 caractères.</div>
                        @error('description_courte')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description complète <span class="text-danger">*</span></label>
                        <x-rich-editor name="description" toolbar="full" :value="old('description', '')" :error="$errors->first('description')" required />
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Image principale</label>
                        <input type="file" id="image" name="image" accept="image/*"
                               class="form-control @error('image') is-invalid @enderror">
                        <div class="form-text">JPG, PNG ou WebP. Max 3 Mo.</div>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-0">
                        <label for="document" class="form-label">Document (business plan, présentation…)</label>
                        <input type="file" id="document" name="document" accept=".pdf,.doc,.docx,.ppt,.pptx"
                               class="form-control @error('document') is-invalid @enderror">
                        <div class="form-text">PDF, Word ou PowerPoint. Max 10 Mo.</div>
                        @error('document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-cash-coin me-1 text-faaci-steel"></i> Financement
                </div>
                <div class="card-body pt-0" x-data="{ type: '{{ old('type_financement', 'fixe') }}' }">
                    <div class="mb-3">
                        <label class="form-label">Type de financement <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="d-block border rounded-3 p-3 cursor-pointer"
                                       :class="type === 'fixe' ? 'border-faaci-steel bg-light' : ''">
                                    <input type="radio" name="type_financement" value="fixe"
                                           x-model="type" class="form-check-input me-2"
                                           {{ old('type_financement','fixe') === 'fixe' ? 'checked' : '' }}>
                                    <strong>Budget fixe</strong>
                                    <p class="text-muted small mb-0 mt-1">Vous fixez un montant cible à atteindre.</p>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="d-block border rounded-3 p-3 cursor-pointer"
                                       :class="type === 'ouvert' ? 'border-faaci-steel bg-light' : ''">
                                    <input type="radio" name="type_financement" value="ouvert"
                                           x-model="type" class="form-check-input me-2"
                                           {{ old('type_financement') === 'ouvert' ? 'checked' : '' }}>
                                    <strong>Budget ouvert</strong>
                                    <p class="text-muted small mb-0 mt-1">Pas de montant cible défini.</p>
                                </label>
                            </div>
                        </div>
                        @error('type_financement')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3" x-show="type === 'fixe'" x-cloak>
                        <label for="montant_cible" class="form-label">Montant cible (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" id="montant_cible" name="montant_cible"
                               class="form-control @error('montant_cible') is-invalid @enderror"
                               value="{{ old('montant_cible') }}" min="1" placeholder="Ex : 1000000"
                               :disabled="type !== 'fixe'">
                        @error('montant_cible')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="date_fin_financement" class="form-label">Fin du financement</label>
                            <input type="date" id="date_fin_financement" name="date_fin_financement"
                                   class="form-control @error('date_fin_financement') is-invalid @enderror"
                                   value="{{ old('date_fin_financement') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            @error('date_fin_financement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="date_debut" class="form-label">Date de début du projet</label>
                            <input type="date" id="date_debut" name="date_debut"
                                   class="form-control @error('date_debut') is-invalid @enderror"
                                   value="{{ old('date_debut') }}">
                            @error('date_debut')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle me-1"></i>
                        Votre projet sera enregistré en <strong>brouillon</strong>. Vous pourrez le soumettre à la validation admin depuis la page de détail.
                    </div>
                    <button type="submit" class="btn btn-faaci-primary w-100">
                        <i class="bi bi-save me-1"></i> Enregistrer le brouillon
                    </button>
                    <a href="{{ route('membre.projets.index') }}" class="btn btn-outline-secondary w-100 mt-2">Annuler</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
