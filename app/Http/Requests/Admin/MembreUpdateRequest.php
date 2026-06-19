<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MembreUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prenom'    => ['required', 'string', 'max:100'],
            'nom'       => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('membre'))],
            'telephone' => ['nullable', 'string', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Cette adresse e-mail est déjà utilisée par un autre compte.',
        ];
    }
}
