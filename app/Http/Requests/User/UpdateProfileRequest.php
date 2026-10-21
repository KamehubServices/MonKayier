<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'required', 'string', 'max:100'],
            'last_name' => ['sometimes', 'required', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'preferred_language' => ['sometimes', 'string', 'in:fr,en,ee,kbp'],
            'bio' => ['nullable', 'string', 'max:500'],
        ];
    }
}
