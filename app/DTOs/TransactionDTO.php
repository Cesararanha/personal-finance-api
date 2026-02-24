<?php

namespace App\DTOs;

use Carbon\Carbon;

class TransactionDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $type,
        public readonly float $amount,
        public readonly ?string $description,
        public readonly Carbon $date,
        public readonly int $categoryId,
        public readonly int $userId,
    ) {}
}
