<?php

namespace App\Http\Requests;

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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|size:11|unique:users,cpf',
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date|before:today',
        ];
    }
}
