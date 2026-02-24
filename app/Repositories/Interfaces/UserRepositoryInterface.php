<?php

namespace App\Repositories\Interfaces;

use App\DTOs\UserDTO;

interface UserRepositoryInterface
{
    public function findById(int $id): ?UserDTO;

    public function findByEmail(string $email): ?UserDTO;

    public function create(UserDTO $dto): UserDTO;
}
