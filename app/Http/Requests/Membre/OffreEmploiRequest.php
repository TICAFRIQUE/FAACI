<?php

namespace App\Http\Requests\Membre;

use App\Models\OffreEmploi;
use Illuminate\Foundation\Http\FormRequest;

class OffreEmploiRequest extends FormRequest
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
            'type_contrat'         => ['required', 'in:'.implode(',', array_keys(OffreEmploi::TYPES_CONTRAT))],
            'localisation'         => ['nullable', 'string', 'max:255'],
            'salaire'              => ['nullable', 'string', 'max:100'],
            'competences_requises' => ['nullable', 'string', 'max:2000'],
            'lien_externe'         => ['nullable', 'url', 'max:500'],
            'date_expiration'      => ['nullable', 'date', 'after:today'],
        ];
    }
}
