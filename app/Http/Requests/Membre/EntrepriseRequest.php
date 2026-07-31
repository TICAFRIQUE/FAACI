<?php

namespace App\Http\Requests\Membre;

use Illuminate\Foundation\Http\FormRequest;

class EntrepriseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'           => ['required', 'string', 'max:200'],
            'secteur'       => ['nullable', 'string', 'max:150'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'localisation'  => ['nullable', 'string', 'max:200'],
            'site_web'      => ['nullable', 'url', 'max:255'],
            'telephone'     => ['nullable', 'string', 'max:30'],
            'email_contact' => ['nullable', 'email', 'max:255'],
            'annee_creation'=> ['nullable', 'digits:4', 'integer', 'min:1900', 'max:'.(int)date('Y')],
            'logo'          => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ];
    }
}
