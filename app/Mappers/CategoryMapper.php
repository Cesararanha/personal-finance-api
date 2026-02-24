<?php

namespace App\Mappers;

use App\DTOs\CategoryDTO;
use App\Models\Category;

class CategoryMapper
{
    public static function fromRequest(array $data): CategoryDTO
    {
        return new CategoryDTO(
            id: null,
            name: $data['name'],
            userId: $data['user_id'],
        );
    }

    public static function toDTO(Category $category): CategoryDTO
    {
        return new CategoryDTO(
            id: $category->id,
            name: $category->name,
            userId: $category->user_id,
        );
    }

    public static function toArray(CategoryDTO $dto): array
    {
        return [
            'id' => $dto->id,
            'name' => $dto->name,
            'user_id' => $dto->userId,
        ];
    }
}
