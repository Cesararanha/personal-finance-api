<?php

namespace App\Mappers;

use App\DTOs\UserDTO;
use App\Models\User;
use Carbon\Carbon;

class UserMapper
{
    public static function fromRequest(array $data): UserDTO
    {
        return new UserDTO(
            id: null,
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            cpf: $data['cpf'],
            phone: $data['phone'],
            birthDate: $data['birth_date'] instanceof Carbon
                ? $data['birth_date']
                : Carbon::parse($data['birth_date']),
        );
    }

    public static function toDTO(User $user): UserDTO
    {
        return new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            password: null,
            cpf: $user->cpf,
            phone: $user->phone,
            birthDate: $user->birth_date instanceof Carbon ? $user->birth_date : Carbon::parse($user->birth_date),
        );
    }

    public static function toArray(UserDTO $dto): array
    {
        return [
            'id' => $dto->id,
            'name' => $dto->name,
            'email' => $dto->email,
            'cpf' => $dto->cpf,
            'phone' => $dto->phone,
            'birth_date' => $dto->birthDate->format('Y-m-d'),
        ];
    }
}
