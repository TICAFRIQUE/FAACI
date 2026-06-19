<?php

namespace App\Http\Requests\Membre;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre'                => ['required', 'string', 'max:255'],
            'description'          => ['required', 'string', 'min:50'],
            'description_courte'   => ['nullable', 'string', 'max:300'],
            'type_financement'     => ['required', Rule::in(['fixe', 'ouvert'])],
            'montant_cible'        => ['nullable', 'numeric', 'min:1', 'required_if:type_financement,fixe'],
            'date_fin_financement' => ['nullable', 'date', 'after:today'],
            'date_debut'           => ['nullable', 'date'],
            'image'                => ['nullable', 'image', 'max:3072', 'mimes:jpg,jpeg,png,webp'],
            'document'             => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,ppt,pptx'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required'                        => 'Le titre du projet est obligatoire.',
            'description.required'                  => 'La description est obligatoire.',
            'description.min'                       => 'La description doit contenir au moins 50 caractères.',
            'type_financement.required'             => 'Le type de financement est obligatoire.',
            'montant_cible.required_if'             => 'Le montant cible est obligatoire pour un financement fixe.',
            'montant_cible.min'                     => 'Le montant cible doit être supérieur à 0.',
            'date_fin_financement.after'            => 'La date de fin de financement doit être dans le futur.',
            'image.max'                             => 'L\'image ne doit pas dépasser 3 Mo.',
            'document.max'                          => 'Le document ne doit pas dépasser 10 Mo.',
            'document.mimes'                        => 'Le document doit être au format PDF, Word ou PowerPoint.',
        ];
    }
}
