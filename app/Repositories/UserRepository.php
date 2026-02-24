<?php

namespace App\Repositories;

use App\DTOs\UserDTO;
use App\Mappers\UserMapper;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly User $model) {}

    public function findById(int $id): ?UserDTO
    {
        $user = $this->model->find($id);

        return $user ? UserMapper::toDTO($user) : null;
    }

    public function findByEmail(string $email): ?UserDTO
    {
        $user = $this->model->where('email', $email)->first();

        return $user ? UserMapper::toDTO($user) : null;
    }

    public function create(UserDTO $dto): UserDTO
    {
        $user = $this->model->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
            'cpf' => $dto->cpf,
            'phone' => $dto->phone,
            'birth_date' => $dto->birthDate,
        ]);

        return UserMapper::toDTO($user);
    }
}
