<?php

namespace App\Http\Requests\Admin;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'titre'            => ['required', 'string', 'max:255'],
            'categorie'        => ['required', 'string', 'max:60'],
            'extrait'          => ['nullable', 'string', 'max:500'],
            'contenu'          => ['required', 'string'],
            'image'            => ['nullable', 'image', 'max:1024'],
            'photos.*'         => ['nullable', 'image', 'max:1024'],
            'statut'           => ['required', 'in:'.Article::STATUT_BROUILLON.','.Article::STATUT_PUBLIE],
            'date_publication' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.max'    => "L'image à la une ne doit pas dépasser 1 Mo.",
            'photos.*.max' => 'Chaque photo de galerie ne doit pas dépasser 1 Mo.',
            'image.image'  => "Le fichier doit être une image (jpg, png, gif, webp…).",
            'photos.*.image' => "Chaque fichier doit être une image (jpg, png, gif, webp…).",
        ];
    }
}
