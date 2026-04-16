<?php

namespace App\Mappers;

use App\DTOs\RecurringTransactionDTO;
use App\Models\RecurringTransaction;
use Carbon\Carbon;

class RecurringTransactionMapper
{
    public static function fromRequest(array $data, int $userId): RecurringTransactionDTO
    {
        return new RecurringTransactionDTO(
            id: null,
            userId: $userId,
            categoryId: (int) $data['category_id'],
            categoryName: null,
            description: $data['description'] ?? null,
            amount: (float) abs($data['amount']),
            frequency: $data['frequency'],
            nextDueDate: Carbon::parse($data['start_date']),
            isActive: true,
        );
    }

    public static function toDTO(RecurringTransaction $model): RecurringTransactionDTO
    {
        return new RecurringTransactionDTO(
            id: $model->id,
            userId: $model->user_id,
            categoryId: $model->category_id,
            categoryName: $model->category?->name,
            description: $model->description,
            amount: $model->amount,
            frequency: $model->frequency,
            nextDueDate: $model->next_due_date instanceof Carbon
                ? $model->next_due_date
                : Carbon::parse($model->next_due_date),
            isActive: $model->is_active,
        );
    }

    public static function toArray(RecurringTransactionDTO $dto): array
    {
        return [
            'id' => $dto->id,
            'category_id' => $dto->categoryId,
            'category_name' => $dto->categoryName,
            'description' => $dto->description,
            'amount' => $dto->amount,
            'frequency' => $dto->frequency,
            'next_due_date' => $dto->nextDueDate->toDateString(),
            'is_active' => $dto->isActive,
        ];
    }
}
