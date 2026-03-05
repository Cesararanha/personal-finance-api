<?php

namespace App\Repositories;

use App\DTOs\MonthlyIncomeDTO;
use App\Mappers\MonthlyIncomeMapper;
use App\Models\MonthlyIncome;
use App\Repositories\Interfaces\MonthlyIncomeRepositoryInterface;
use Illuminate\Support\Collection;

class MonthlyIncomeRepository implements MonthlyIncomeRepositoryInterface
{
    public function __construct(private readonly MonthlyIncome $model) {}

    public function create(MonthlyIncomeDTO $dto): MonthlyIncomeDTO
    {
        $income = $this->model->create([
            'user_id' => $dto->userId,
            'amount' => $dto->amount,
            'description' => $dto->description,
            'received_at' => $dto->receivedAt->format('Y-m-d'),
        ]);

        return MonthlyIncomeMapper::toDTO($income);
    }

    public function findByUser(int $userId, ?string $month = null): Collection
    {
        $query = $this->model->where('user_id', $userId);

        if ($month) {
            $query->whereYear('received_at', substr($month, 0, 4))
                ->whereMonth('received_at', substr($month, 5, 2));
        }

        return $query->orderByDesc('received_at')->get()
            ->map(fn (MonthlyIncome $i) => MonthlyIncomeMapper::toDTO($i));
    }

    public function findById(int $id, int $userId): ?MonthlyIncomeDTO
    {
        $income = $this->model->where('id', $id)->where('user_id', $userId)->first();

        return $income ? MonthlyIncomeMapper::toDTO($income) : null;
    }

    public function getTotalByUser(int $userId): float
    {
        return (float) $this->model->where('user_id', $userId)->sum('amount');
    }

    public function delete(int $id, int $userId): bool
    {
        $income = $this->model->where('id', $id)->where('user_id', $userId)->first();
        if (! $income) {
            return false;
        }

        return $income->delete();
    }
}
