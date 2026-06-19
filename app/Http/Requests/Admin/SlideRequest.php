<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SlideRequest extends FormRequest
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
            'image.max'   => "L'image de fond ne doit pas dépasser 1 Mo.",
            'image.image' => 'Le fichier doit être une image (jpg, png, gif, webp…).',
        ];
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'sous_titre' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:1024'],
            'libelle_bouton_1' => ['nullable', 'string', 'max:60'],
            'lien_bouton_1' => ['nullable', 'string', 'max:255'],
            'libelle_bouton_2' => ['nullable', 'string', 'max:60'],
            'lien_bouton_2' => ['nullable', 'string', 'max:255'],
            'actif' => ['nullable', 'boolean'],
        ];
    }
}
