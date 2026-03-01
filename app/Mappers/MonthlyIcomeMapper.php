<?php

namespace App\Mappers;

use App\DTOs\MonthlyIncomeDTO;
use App\Models\MonthlyIncome;
use Carbon\Carbon;

class MonthlyIncomeMapper
{
    public static function fromRequest(array $data, int $userId): MonthlyIncomeDTO
    {
        return new MonthlyIncomeDTO(
            id: null,
            userId: $userId,
            amount: (float) abs($data['amount']),
            description: $data['description'] ?? null,
            receivedAt: Carbon::parse($data['received_at']),
        );
    }

    public static function toDTO(MonthlyIncome $income): MonthlyIncomeDTO
    {
        return new MonthlyIncomeDTO(
            id: $income->id,
            userId: $income->user_id,
            amount: $income->amount,
            description: $income->description,
            receivedAt: $income->received_at instanceof Carbon
                ? $income->received_at
                : Carbon::parse($income->received_at),
        );
    }

    public static function toArray(MonthlyIncomeDTO $dto): array
    {
        return [
            'id'          => $dto->id,
            'amount'      => $dto->amount,
            'description' => $dto->description,
            'received_at' => $dto->receivedAt->format('Y-m-d'),
        ];
    }
}
