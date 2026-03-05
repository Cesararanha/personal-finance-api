<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavingMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date_format:Y-m-d',
        ];
    }
}
