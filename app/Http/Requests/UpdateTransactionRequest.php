<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'sometimes|required|in:income,expense',
            'amount' => 'sometimes|required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'date' => 'sometimes|required|date',
            'category_id' => 'sometimes|required|exists:categories,id',
        ];
    }
}
