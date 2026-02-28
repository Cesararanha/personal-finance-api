<?php

namespace App\Mappers;

use App\DTOs\TransactionDTO;
use App\Models\Transaction;

class TransactionMapper
{
    public static function fromRequest(array $data, int $userId): TransactionDTO
    {
        return new TransactionDTO(
            id: null,
            type: $data['type'],
            amount: (float) abs($data['amount']),
            description: $data['description'] ?? null,
            date: \Carbon\Carbon::parse($data['date']),
            categoryId: $data['category_id'],
            userId: $userId,
        );
    }

    public static function toDTO(Transaction $transaction): TransactionDTO
    {
        return new TransactionDTO(
            id: $transaction->id,
            type: $transaction->type,
            amount: $transaction->amount,
            description: $transaction->description ?? null,
            date: $transaction->date instanceof \Carbon\Carbon ? $transaction->date : \Carbon\Carbon::parse($transaction->date),
            categoryId: $transaction->category_id,
            userId: $transaction->user_id,
        );
    }

    public static function toArray(TransactionDTO $dto): array
    {
        return [
            'id' => $dto->id,
            'type' => $dto->type,
            'amount' => $dto->amount,
            'description' => $dto->description,
            'date' => $dto->date->toDateString(),
            'category_id' => $dto->categoryId,
        ];
    }
}
