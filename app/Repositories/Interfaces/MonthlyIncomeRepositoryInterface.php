<?php

namespace App\Repositories\Interfaces;

use App\DTOs\MonthlyIncomeDTO;
use Illuminate\Support\Collection;

interface MonthlyIncomeRepositoryInterface
{
    public function create(MonthlyIncomeDTO $dto): MonthlyIncomeDTO;

    public function findByUser(int $userId, ?string $month = null): Collection;

    public function findById(int $id, int $userId): ?MonthlyIncomeDTO;

    public function getTotalByUser(int $userId): float;

    public function delete(int $id, int $userId): bool;
}
