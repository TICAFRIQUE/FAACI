<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdministrateurRequest extends FormRequest
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
            'email'     => ['required', 'email', 'max:255', 'unique:users,email'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'role'      => ['required', Rule::in(['admin', 'super_admin'])],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'role.in'      => 'Le rôle sélectionné est invalide.',
        ];
    }
}
