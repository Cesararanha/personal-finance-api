<?php

namespace App\DTOs;

use Carbon\Carbon;

class SabuntransactionDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $savingsId,
        public readonly int $userId,
        public readonly string $type,
        public readonly float $amount,
        public readonly ?string $description,
        public readonly Carbon $date,
    ) {}
}
