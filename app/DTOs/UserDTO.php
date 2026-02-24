<?php

namespace App\DTOs;

use Carbon\Carbon;

class UserDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $password,
        public readonly string $cpf,
        public readonly string $phone,
        public readonly Carbon $birthDate,

    ) {}
}
