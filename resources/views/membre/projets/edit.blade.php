@extends('layouts.app')

@section('title', 'Modifier le projet')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.projets.index') }}">Projets</a></li>
    <li class="breadcrumb-item"><a href="{{ route('membre.projets.show', $projet) }}">{{ Str::limit($projet->titre, 30) }}</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Modifier le projet</h4>
    <a href="{{ route('membre.projets.show', $projet) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<form method="POST" action="{{ route('membre.projets.update', $projet) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-lightbulb me-1 text-faaci-steel"></i> Informations du projet
                </div>
                <div class="card-body pt-0">
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                        <input type="text" id="titre" name="titre"
                               class="form-control @error('titre') is-invalid @enderror"
                               value="{{ old('titre', $projet->titre) }}" required>
                        @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description_courte" class="form-label">Résumé court</label>
                        <input type="text" id="description_courte" name="description_courte"
                               class="form-control @error('description_courte') is-invalid @enderror"
                               value="{{ old('description_courte', $projet->description_courte) }}" maxlength="300">
                        @error('description_courte')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description complète <span class="text-danger">*</span></label>
                        <x-rich-editor name="description" toolbar="full" :value="old('description', $projet->description)" :error="$errors->first('description')" required />
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Image principale</label>
                        @if ($projet->image_url)
                            <div class="mb-2">
                                <img src="{{ $projet->image_url }}" alt="" class="rounded" style="height:80px;object-fit:cover;">
                                <div class="form-text">Une nouvelle image remplacera l'actuelle.</div>
                            </div>
                        @endif
                        <input type="file" id="image" name="image" accept="image/*"
                               class="form-control @error('image') is-invalid @enderror">
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-0">
                        <label for="document" class="form-label">Document (business plan, présentation…)</label>
                        @php $docActuel = $projet->getFirstMedia('documents'); @endphp
                        @if ($docActuel)
                            <div class="mb-2 d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-pdf text-danger"></i>
                                <a href="{{ $docActuel->getUrl() }}" target="_blank" class="small">
                                    {{ $docActuel->file_name }}
                                </a>
                                <span class="text-muted small">(un nouveau fichier remplacera l'actuel)</span>
                            </div>
                        @endif
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
                <div class="card-body pt-0" x-data="{ type: '{{ old('type_financement', $projet->type_financement) }}' }">
                    <div class="mb-3">
                        <label class="form-label">Type de financement <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="d-block border rounded-3 p-3 cursor-pointer"
                                       :class="type === 'fixe' ? 'border-faaci-steel bg-light' : ''">
                                    <input type="radio" name="type_financement" value="fixe" x-model="type"
                                           class="form-check-input me-2"
                                           {{ old('type_financement', $projet->type_financement) === 'fixe' ? 'checked' : '' }}>
                                    <strong>Budget fixe</strong>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="d-block border rounded-3 p-3 cursor-pointer"
                                       :class="type === 'ouvert' ? 'border-faaci-steel bg-light' : ''">
                                    <input type="radio" name="type_financement" value="ouvert" x-model="type"
                                           class="form-check-input me-2"
                                           {{ old('type_financement', $projet->type_financement) === 'ouvert' ? 'checked' : '' }}>
                                    <strong>Budget ouvert</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" x-show="type === 'fixe'" x-cloak>
                        <label for="montant_cible" class="form-label">Montant cible (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" id="montant_cible" name="montant_cible"
                               class="form-control @error('montant_cible') is-invalid @enderror"
                               value="{{ old('montant_cible', $projet->montant_cible) }}" min="1"
                               :disabled="type !== 'fixe'">
                        @error('montant_cible')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="date_fin_financement" class="form-label">Fin du financement</label>
                            <input type="date" id="date_fin_financement" name="date_fin_financement"
                                   class="form-control"
                                   value="{{ old('date_fin_financement', $projet->date_fin_financement?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-sm-6">
                            <label for="date_debut" class="form-label">Date de début</label>
                            <input type="date" id="date_debut" name="date_debut"
                                   class="form-control"
                                   value="{{ old('date_debut', $projet->date_debut?->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <button type="submit" class="btn btn-faaci-primary w-100">
                        <i class="bi bi-save me-1"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('membre.projets.show', $projet) }}" class="btn btn-outline-secondary w-100 mt-2">Annuler</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
