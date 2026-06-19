<?php

namespace App\Http\Requests\Membre;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfilUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prenom'          => ['required', 'string', 'max:100'],
            'nom'             => ['required', 'string', 'max:100'],
            'email'           => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user())],
            'telephone'       => ['nullable', 'string', 'max:30'],
            'bio'             => ['nullable', 'string', 'max:1000'],
            'promotion_aiesec'=> ['nullable', 'string', 'max:10'],
            'secteur'         => ['nullable', 'string', 'max:150'],
            'ville'           => ['nullable', 'string', 'max:100'],
            'competences'     => ['nullable', 'array'],
            'competences.*'   => ['string', 'max:80'],
        ];
    }
}
