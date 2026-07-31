<?php

namespace App\Http\Requests\Membre;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nature'        => ['required', Rule::in(['argent', 'materiel', 'autre'])],
            'libelle'       => ['required', 'string', 'max:255'],
            'montant'       => ['nullable', 'required_if:nature,argent', 'numeric', 'min:100'],
            'valeur_estimee'=> ['nullable', 'string', 'max:100'],
            'moyen_paiement'=> ['nullable', 'required_if:nature,argent', Rule::in(['cash', 'orange_money', 'wave', 'virement', 'autre'])],
            'description'   => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'montant.required_if'       => 'Le montant est obligatoire pour un don financier.',
            'moyen_paiement.required_if'=> 'Le moyen de paiement est obligatoire pour un don financier.',
        ];
    }
}
