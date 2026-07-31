@extends('layouts.app')

@section('title', 'Référencer mon entreprise')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.entreprises.index') }}">Entreprises Alumni</a></li>
    <li class="breadcrumb-item active">Nouvelle entreprise</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Référencer mon entreprise</h4>
    <a href="{{ route('membre.entreprises.mes-entreprises') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <form method="POST" action="{{ route('membre.entreprises.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-building me-1 text-faaci-steel"></i> Informations de l'entreprise
                </div>
                <div class="card-body pt-0">
                    <div class="row g-3">
                        <div class="col-sm-8">
                            <label for="nom" class="form-label">Nom de l'entreprise <span class="text-danger">*</span></label>
                            <input type="text" id="nom" name="nom"
                                   class="form-control @error('nom') is-invalid @enderror"
                                   value="{{ old('nom') }}" required maxlength="200">
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-4">
                            <label for="annee_creation" class="form-label">Année de création</label>
                            <input type="number" id="annee_creation" name="annee_creation"
                                   class="form-control @error('annee_creation') is-invalid @enderror"
                                   value="{{ old('annee_creation') }}" min="1900" max="{{ date('Y') }}"
                                   placeholder="{{ date('Y') }}">
                            @error('annee_creation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="secteur" class="form-label">Secteur d'activité</label>
                            <select id="secteur" name="secteur"
                                    class="form-select @error('secteur') is-invalid @enderror">
                                <option value="">— Choisir un secteur —</option>
                                @foreach ($secteurs as $s)
                                    <option value="{{ $s }}" {{ old('secteur') === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                            @error('secteur')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="localisation" class="form-label">Localisation</label>
                            <input type="text" id="localisation" name="localisation"
                                   class="form-control @error('localisation') is-invalid @enderror"
                                   value="{{ old('localisation') }}" placeholder="Ex : Abidjan, Côte d'Ivoire">
                            @error('localisation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      maxlength="2000">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-link-45deg me-1 text-faaci-steel"></i> Contacts & liens
                </div>
                <div class="card-body pt-0">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="text" id="telephone" name="telephone"
                                   class="form-control @error('telephone') is-invalid @enderror"
                                   value="{{ old('telephone') }}" placeholder="+225 07 00 00 00 00">
                            @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="email_contact" class="form-label">E-mail de contact</label>
                            <input type="email" id="email_contact" name="email_contact"
                                   class="form-control @error('email_contact') is-invalid @enderror"
                                   value="{{ old('email_contact') }}">
                            @error('email_contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="site_web" class="form-label">Site web</label>
                            <input type="url" id="site_web" name="site_web"
                                   class="form-control @error('site_web') is-invalid @enderror"
                                   value="{{ old('site_web') }}" placeholder="https://…">
                            @error('site_web')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                    <i class="bi bi-image me-1 text-faaci-steel"></i> Logo
                </div>
                <div class="card-body pt-0">
                    <input type="file" id="logo" name="logo"
                           class="form-control @error('logo') is-invalid @enderror"
                           accept="image/*">
                    <div class="form-text">Format JPG, PNG ou WebP. Max 2 Mo.</div>
                    @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('membre.entreprises.mes-entreprises') }}" class="btn btn-outline-secondary">Annuler</a>
                <button type="submit" class="btn btn-faaci-primary">
                    <i class="bi bi-check-lg me-1"></i> Soumettre pour validation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
