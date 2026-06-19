@props([
    'name',
    'value'       => '',
    'toolbar'     => 'full',
    'rows'        => 8,
    'required'    => false,
    'placeholder' => '',
    'error'       => null,
])

<textarea
    name="{{ $name }}"
    rows="{{ $rows }}"
    class="form-control rich-editor-{{ $toolbar }} {{ $error ? 'is-invalid' : '' }}"
    placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }}
>{!! old($name, $value) !!}</textarea>

@if ($error)
    <div class="invalid-feedback">{{ $error }}</div>
@endif
