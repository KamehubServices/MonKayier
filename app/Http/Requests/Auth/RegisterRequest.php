<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'required_without:phone', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'required_without:email', 'string', 'max:30', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8'],
            'preferred_language' => ['nullable', 'string', 'in:fr,en,ee,kbp'], // fr, en, éwé, kabiyè
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Cette adresse e-mail est déjà associée à un compte.',
            'phone.unique' => 'Ce numéro de téléphone est déjà associé à un compte.',
            'password.min' => 'Le mot de passe doit comporter au moins 8 caractères.',
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
        ];
    }
}
