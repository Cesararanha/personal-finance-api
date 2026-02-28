<?php

namespace App\Repositories\Interfaces;

use App\DTOs\TransactionDTO;
use Illuminate\Support\Collection;

interface TransactionRepositoryInterface
{
    public function findAll(int $userId): Collection;

    public function findById(int $id, int $userId): ?TransactionDTO;

    public function findByMonth(int $userId, int $month, int $year): Collection;

    public function findByCategory(int $categoryId, int $userId): Collection;

    public function categoryExistsForUser(int $categoryId, int $userId): bool;

    public function findFiltered(int $userId, ?string $month = null, ?int $categoryId = null): Collection;

    public function create(TransactionDTO $dto): TransactionDTO;

    public function update(int $id, TransactionDTO $dto): ?TransactionDTO;

    public function delete(int $id, int $userId): bool;
}
