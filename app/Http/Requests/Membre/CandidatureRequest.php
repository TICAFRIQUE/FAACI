<?php

namespace App\Http\Requests\Membre;

use Illuminate\Foundation\Http\FormRequest;

class CandidatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lettre_motivation' => ['nullable', 'string', 'max:3000'],
            'cv'                => ['nullable', 'file', 'max:5120', 'mimes:pdf,doc,docx'],
        ];
    }
}
