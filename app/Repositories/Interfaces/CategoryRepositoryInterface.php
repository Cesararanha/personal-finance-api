<?php

namespace App\Repositories\Interfaces;

use App\DTOs\CategoryDTO;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    public function findAll(int $userId): Collection;

    public function findById(int $id, int $userId): ?CategoryDTO;

    public function create(CategoryDTO $dto): CategoryDTO;

    public function update(int $id, CategoryDTO $dto): ?CategoryDTO;

    public function delete(int $id, int $userId): bool;

    public function archive(int $id, int $userId): bool;
}
