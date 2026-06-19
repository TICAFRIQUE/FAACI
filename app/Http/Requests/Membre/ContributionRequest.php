<?php

namespace App\Http\Requests\Membre;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'montant_promis' => ['required', 'numeric', 'min:1'],
            'note'           => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'montant_promis.required' => 'Le montant est obligatoire.',
            'montant_promis.min'      => 'Le montant doit être supérieur à 0.',
        ];
    }
}
