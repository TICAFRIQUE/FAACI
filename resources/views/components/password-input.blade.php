@props([
    'id'           => 'password',
    'name'         => 'password',
    'autocomplete' => 'current-password',
])

<div class="input-group has-validation">
    <input type="password"
           id="{{ $id }}"
           name="{{ $name }}"
           autocomplete="{{ $autocomplete }}"
           {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
    <button type="button"
            class="btn btn-outline-secondary"
            onclick="togglePwd('{{ $id }}')"
            tabindex="-1"
            aria-label="Afficher/masquer le mot de passe">
        <i class="bi bi-eye" id="eye-{{ $id }}"></i>
    </button>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@once
<script>
function togglePwd(id) {
    const input = document.getElementById(id);
    const icon  = document.getElementById('eye-' + id);
    const show  = input.type === 'password';
    input.type      = show ? 'text' : 'password';
    icon.className  = show ? 'bi bi-eye-slash' : 'bi bi-eye';
}
</script>
@endonce
