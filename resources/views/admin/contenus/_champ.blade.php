@php
    $isRich   = $contenu->type === \App\Models\ContenuSection::TYPE_RICHTEXT;
    $isLong   = $contenu->type === \App\Models\ContenuSection::TYPE_TEXTAREA;
    $isNombre = $contenu->type === \App\Models\ContenuSection::TYPE_NOMBRE;
    $isImage  = $contenu->type === \App\Models\ContenuSection::TYPE_IMAGE;
    $colClass = ($isRich || $isLong) ? 'col-12' : ($isImage ? 'col-md-6' : 'col-md-6');
    $name     = "contenus[{$contenu->cle}]";
    $imgName  = "images[{$contenu->cle}]";
    $errorKey = "contenus.{$contenu->cle}";
    $imgError = "images.{$contenu->cle}";
    $valeur   = old($errorKey, $contenu->valeur);
@endphp

<div class="{{ $colClass }}">
    <label class="form-label">{{ $contenu->libelle }}</label>

    @if ($isImage)
        <input type="file" id="cs_{{ $contenu->cle }}" name="{{ $imgName }}" accept="image/*"
               class="form-control @error($imgError) is-invalid @enderror">
        <div id="cs_{{ $contenu->cle }}_err" class="text-danger small mt-1" style="display:none"></div>
        @error($imgError) <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if ($contenu->valeur)
            <div class="mt-2">
                <img src="{{ $contenu->valeur }}" alt="" class="img-thumbnail" style="max-height:120px;object-fit:cover;">
                <div class="form-text">Image actuelle. Sélectionnez un nouveau fichier pour la remplacer.</div>
            </div>
        @endif
        @push('scripts')
        <script>
        (function(){
            const MAX = 1024*1024;
            document.getElementById('cs_{{ $contenu->cle }}').addEventListener('change', function(){
                const err = document.getElementById('cs_{{ $contenu->cle }}_err');
                if(this.files[0] && this.files[0].size > MAX){
                    err.textContent = "L'image ne doit pas dépasser 1 Mo (" + (this.files[0].size/1048576).toFixed(2) + " Mo).";
                    err.style.display = 'block';
                    this.value = '';
                } else { err.style.display = 'none'; }
            });
        })();
        </script>
        @endpush
    @elseif ($isRich)
        <x-rich-editor :name="$name" toolbar="full" :value="$valeur" :error="$errors->first($errorKey)" />
    @elseif ($isLong)
        <textarea name="{{ $name }}" rows="3"
                  class="form-control @error($errorKey) is-invalid @enderror">{{ $valeur }}</textarea>
        @error($errorKey) <div class="invalid-feedback">{{ $message }}</div> @enderror
    @elseif ($isNombre)
        <input type="number" name="{{ $name }}" value="{{ $valeur }}"
               class="form-control @error($errorKey) is-invalid @enderror">
        @error($errorKey) <div class="invalid-feedback">{{ $message }}</div> @enderror
    @else
        <input type="text" name="{{ $name }}" value="{{ $valeur }}"
               class="form-control @error($errorKey) is-invalid @enderror">
        @error($errorKey) <div class="invalid-feedback">{{ $message }}</div> @enderror
    @endif
</div>
