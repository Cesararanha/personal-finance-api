<?php

namespace App\DTOs;

use Carbon\Carbon;

class RecurringTransactionDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $userId,
        public readonly int $categoryId,
        public readonly ?string $categoryName,
        public readonly ?string $description,
        public readonly float $amount,
        public readonly string $frequency,
        public readonly Carbon $nextDueDate,
        public readonly bool $isActive,
    ) {}
}
