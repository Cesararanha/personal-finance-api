<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:pdf,csv'],
            'filters' => ['nullable', 'array'],
            'filters.month' => ['nullable', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'filters.start_date' => ['nullable', 'date'],
            'filters.end_date' => ['nullable', 'date', 'after_or_equal:filters.start_date'],
            'filters.category_id' => ['nullable', 'integer'],
        ];
    }
}
