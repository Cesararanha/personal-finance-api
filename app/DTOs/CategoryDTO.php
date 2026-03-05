<?php

namespace App\DTOs;

class CategoryDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly int $userId,
        public readonly bool $isActive = true,
    ) {}
}
