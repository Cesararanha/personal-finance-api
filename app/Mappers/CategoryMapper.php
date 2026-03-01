<?php

namespace App\Mappers;

use App\DTOs\CategoryDTO;
use App\Models\Category;

class CategoryMapper
{
    public static function fromRequest(array $data, int $userId): CategoryDTO
    {
        return new CategoryDTO(
            id: null,
            name: $data['name'],
            userId: $userId,
            isActive: true,
        );
    }

    public static function toDTO(Category $category): CategoryDTO
    {
        return new CategoryDTO(
            id: $category->id,
            name: $category->name,
            userId: $category->user_id,
            isActive: $category->is_active,
        );
    }

    public static function toArray(CategoryDTO $dto): array
    {
        return [
            'id' => $dto->id,
            'name' => $dto->name,
            'user_id' => $dto->userId,
            'is_active' => $dto->isActive,
        ];
    }
}
