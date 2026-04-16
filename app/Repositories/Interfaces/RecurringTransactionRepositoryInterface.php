<?php

namespace App\Repositories\Interfaces;

use App\DTOs\RecurringTransactionDTO;
use Illuminate\Support\Collection;

interface RecurringTransactionRepositoryInterface
{
    public function findAll(int $userId): Collection;

    public function findById(int $id, int $userId): ?RecurringTransactionDTO;

    public function findDue(): Collection;

    public function create(RecurringTransactionDTO $dto): RecurringTransactionDTO;

    public function update(int $id, int $userId, array $data): ?RecurringTransactionDTO;

    public function delete(int $id, int $userId): bool;
}
