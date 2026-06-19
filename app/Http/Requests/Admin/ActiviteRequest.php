<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ActiviteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'icone'       => ['required', 'string', 'max:60'],
            'titre'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'actif'       => ['boolean'],
        ];
    }
}
