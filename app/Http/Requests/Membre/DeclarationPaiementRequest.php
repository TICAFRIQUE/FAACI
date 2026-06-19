<?php

namespace App\Http\Requests\Membre;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeclarationPaiementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'montant_paye'   => ['required', 'numeric', 'min:1'],
            'moyen_paiement' => ['required', Rule::in(['cash', 'orange_money', 'wave', 'virement', 'autre'])],
            'note'           => ['nullable', 'string', 'max:500'],
            'preuve'         => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,webp,pdf'],
        ];
    }

    public function messages(): array
    {
        return [
            'montant_paye.required'   => 'Le montant payé est obligatoire.',
            'montant_paye.min'        => 'Le montant doit être supérieur à 0.',
            'moyen_paiement.required' => 'Veuillez indiquer le moyen de paiement utilisé.',
            'preuve.max'              => 'La pièce justificative ne doit pas dépasser 4 Mo.',
        ];
    }
}
