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
            'date' => 'sometimes|required|date_format:Y-m-d',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
        ];
    }
}
