@extends('layouts.admin')

@section('title', 'Paramètres du site')
@section('page-title', 'Paramètres du site')

@section('content')
    <p class="text-muted mb-4">Coordonnées, réseaux sociaux et textes globaux affichés sur le site vitrine.</p>

    <form method="POST" action="{{ route('admin.parametres.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Logo --}}
        @php $logoParam = $parametres->firstWhere('cle', 'logo'); @endphp
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold text-faaci-navy">Logo du site</div>
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <label class="form-label">Logo (header &amp; footer) <span class="text-muted small">— max 1 Mo</span></label>
                        <input type="file" id="logoInput" name="logo" accept="image/*"
                               class="form-control @error('logo') is-invalid @enderror">
                        <div id="logoError" class="text-danger small mt-1" style="display:none"></div>
                        @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Formats acceptés : JPG, PNG, WEBP. Sélectionnez un fichier pour remplacer le logo actuel.</div>
                    </div>
                    <div class="col-md-6">
                        @php $logoUrl = $logoParam?->valeur ?: asset('images/logo.jpg'); @endphp
                        <p class="form-label mb-1">Aperçu actuel</p>
                        <img id="logoPreview" src="{{ $logoUrl }}" alt="Logo actuel"
                             class="img-thumbnail rounded-circle" style="width:80px;height:80px;object-fit:cover;">
                    </div>
                </div>
            </div>
        </div>

        {{-- Autres paramètres (on exclut le logo qui est géré via file) --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold text-faaci-navy">Coordonnées &amp; textes</div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach ($parametres->where('cle', '!=', 'logo') as $parametre)
                        @php
                            $estLong  = str_contains($parametre->cle, 'texte') || str_contains($parametre->cle, 'adresse') || str_contains($parametre->cle, 'copyright');
                            $colClass = $estLong ? 'col-12' : 'col-md-6';
                            $name     = "parametres[{$parametre->cle}]";
                        @endphp
                        <div class="{{ $colClass }}">
                            <label class="form-label">{{ $parametre->libelle }}</label>
                            @if ($estLong)
                                <textarea name="{{ $name }}" rows="2"
                                          class="form-control @error("parametres.{$parametre->cle}") is-invalid @enderror">{{ old("parametres.{$parametre->cle}", $parametre->valeur) }}</textarea>
                            @else
                                <input type="text" name="{{ $name }}" value="{{ old("parametres.{$parametre->cle}", $parametre->valeur) }}"
                                       class="form-control @error("parametres.{$parametre->cle}") is-invalid @enderror">
                            @endif
                            @error("parametres.{$parametre->cle}") <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-faaci-navy">
            <i class="bi bi-check-lg me-1"></i> Enregistrer les modifications
        </button>
    </form>

@push('scripts')
<script>
(function () {
    const MAX = 1024 * 1024;
    const input   = document.getElementById('logoInput');
    const errEl   = document.getElementById('logoError');
    const preview = document.getElementById('logoPreview');

    input.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        if (file.size > MAX) {
            errEl.textContent = 'Le logo ne doit pas dépasser 1 Mo (fichier : ' + (file.size / 1048576).toFixed(2) + ' Mo).';
            errEl.style.display = 'block';
            this.value = '';
            return;
        }
        errEl.style.display = 'none';
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; };
        reader.readAsDataURL(file);
    });
})();
</script>
@endpush
@endsection
