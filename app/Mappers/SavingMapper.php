<?php

namespace App\Mappers;

use App\DTOs\SavingDTO;
use App\Models\Saving;

class SavingMapper
{
    public static function fromRequest(array $data, int $userId): SavingDTO
    {
        return new SavingDTO(
            id: null,
            userId: $userId,
            name: $data['name'],
            description: $data['description'] ?? null,
            goalAmount: isset($data['goal_amount']) ? (float) $data['goal_amount'] : null,
            balance: 0,
        );
    }

    public static function toDTO(Saving $saving): SavingDTO
    {
        return new SavingDTO(
            id: $saving->id,
            userId: $saving->user_id,
            name: $saving->name,
            description: $saving->description,
            goalAmount: $saving->goal_amount,
            balance: $saving->balance,
        );
    }

    public static function toArray(SavingDTO $dto): array
    {
        return [
            'id' => $dto->id,
            'name' => $dto->name,
            'description' => $dto->description,
            'goal_amount' => $dto->goalAmount,
            'balance' => $dto->balance,
        ];
    }
}
