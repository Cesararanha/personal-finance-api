<?php

namespace App\Repositories\Interfaces;

use App\DTOs\SavingDTO;
use App\DTOs\SavingTransactionDTO;
use Illuminate\Support\Collection;

interface SavingRepositoryInterface
{
    public function findAll(int $userId): Collection;
    public function findById(int $id, int $userId): ?SavingDTO;
    public function create(SavingDTO $dto): SavingDTO;
    public function update(int $id, SavingDTO $dto): ?SavingDTO;
    public function delete(int $id, int $userId): bool;
    public function deposit(int $id, int $userId, float $amount, ?string $description, string $date): SavingDTO;
    public function withdraw(int $id, int $userId, float $amount, ?string $description, string $date): SavingDTO;
    public function getHistory(int $id, int $userId): Collection;
    public function getTotalBalance(int $userId): float;
}
