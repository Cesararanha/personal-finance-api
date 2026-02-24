<?php

namespace App\Repositories;

use App\DTOs\CategoryDTO;
use App\Mappers\CategoryMapper;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private readonly Category $model) {}

    public function findAll(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get()->map(fn (Category $category) => CategoryMapper::toDTO($category));
    }

    public function findById(int $id, int $userId): ?CategoryDTO
    {
        $category = $this->model->where('id', $id)->where('user_id', $userId)->first();

        return $category ? CategoryMapper::toDTO($category) : null;
    }

    public function create(CategoryDTO $dto): CategoryDTO
    {
        $category = $this->model->create([
            'name' => $dto->name,
            'user_id' => $dto->userId,
        ]);

        return CategoryMapper::toDTO($category);
    }

    public function update(int $id, CategoryDTO $dto): ?CategoryDTO
    {
        $category = $this->model->where('id', $id)->where('user_id', $dto->userId)->first();
        if (! $category) {
            return null;
        }

        $category->update([
            'name' => $dto->name,
        ]);

        return CategoryMapper::toDTO($category->fresh());
    }

    public function delete(int $id, int $userId): bool
    {
        $category = $this->model->where('id', $id)->where('user_id', $userId)->first();
        if (! $category) {
            return false;
        }

        return $category->delete();
    }
}
