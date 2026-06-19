<?php

namespace App\Http\Requests\Admin;

use App\Models\Evenement;
use Illuminate\Foundation\Http\FormRequest;

class EvenementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'image.max'   => "L'image ne doit pas dépasser 1 Mo.",
            'image.image' => "Le fichier doit être une image (jpg, png, gif, webp…).",
        ];
    }

    public function rules(): array
    {
        return [
            'titre'        => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string', 'max:5000'],
            'type'         => ['required', 'in:'.implode(',', array_keys(Evenement::TYPES))],
            'date_debut'   => ['required', 'date'],
            'date_fin'     => ['nullable', 'date', 'after_or_equal:date_debut'],
            'lieu'         => ['nullable', 'string', 'max:255'],
            'lien_visio'   => ['nullable', 'url', 'max:500'],
            'capacite_max' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'est_public'   => ['boolean'],
            'image'        => ['nullable', 'image', 'max:1024'],
            'statut'       => ['required', 'in:'.Evenement::STATUT_BROUILLON.','.Evenement::STATUT_PUBLIE],
        ];
    }
}
