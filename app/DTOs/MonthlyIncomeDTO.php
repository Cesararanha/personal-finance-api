<?php

namespace App\DTOs;

use Carbon\Carbon;

class MonthlyIncomeDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $userId,
        public readonly float $amount,
        public readonly ?string $description,
        public readonly Carbon $receivedAt,
    ) {}
}
