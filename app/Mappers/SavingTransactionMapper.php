<?php

namespace App\Mappers;

use App\DTOs\SavingTransactionDTO;
use App\Models\SavingTransaction;
use Carbon\Carbon;

class SavingTransactionMapper
{
    public static function fromRequest(array $data, int $savingsId, int $userId): SavingTransactionDTO
    {
        return new SavingTransactionDTO(
            id: null,
            savingsId: $savingsId,
            userId: $userId,
            type: $data['type'],
            amount: (float) abs($data['amount']),
            description: $data['description'] ?? null,
            date: Carbon::parse($data['date']),
        );
    }

    public static function toDTO(SavingTransaction $transaction): SavingTransactionDTO
    {
        return new SavingTransactionDTO(
            id: $transaction->id,
            savingsId: $transaction->savings_id,
            userId: $transaction->user_id,
            type: $transaction->type,
            amount: $transaction->amount,
            description: $transaction->description,
            date: $transaction->date instanceof Carbon
                ? $transaction->date
                : Carbon::parse($transaction->date),
        );
    }

    public static function toArray(SavingTransactionDTO $dto): array
    {
        return [
            'id'          => $dto->id,
            'savings_id'  => $dto->savingsId,
            'type'        => $dto->type,
            'amount'      => $dto->amount,
            'description' => $dto->description,
            'date'        => $dto->date->format('Y-m-d'),
        ];
    }
}
