<?php

namespace App\DTOs;

class SavingDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $userId,
        public readonly string $name,
        public readonly ?string $description,
        public readonly ?float $goalAmount,
        public readonly float $balance,
    ) {}
}
