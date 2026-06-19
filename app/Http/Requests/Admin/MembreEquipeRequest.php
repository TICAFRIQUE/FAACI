<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MembreEquipeRequest extends FormRequest
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
            'photo.max'   => 'La photo ne doit pas dépasser 1 Mo.',
            'photo.image' => 'Le fichier doit être une image (jpg, png, gif, webp…).',
        ];
    }

    public function rules(): array
    {
        return [
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'fonction' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'max:1024'],
            'actif' => ['nullable', 'boolean'],
        ];
    }
}
