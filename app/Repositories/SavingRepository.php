<?php

namespace App\Repositories;

use App\DTOs\SavingDTO;
use App\Mappers\SavingMapper;
use App\Mappers\SavingTransactionMapper;
use App\Models\Saving;
use App\Models\SavingTransaction;
use App\Repositories\Interfaces\SavingRepositoryInterface;
use Illuminate\Support\Collection;

class SavingRepository implements SavingRepositoryInterface
{
    public function __construct(
        private readonly Saving $model,
        private readonly SavingTransaction $transactionModel,
    ) {}

    public function findAll(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get()
            ->map(fn(Saving $s) => SavingMapper::toDTO($s));
    }

    public function findById(int $id, int $userId): ?SavingDTO
    {
        $saving = $this->model->where('id', $id)->where('user_id', $userId)->first();
        return $saving ? SavingMapper::toDTO($saving) : null;
    }

    public function create(SavingDTO $dto): SavingDTO
    {
        $saving = $this->model->create([
            'user_id'     => $dto->userId,
            'name'        => $dto->name,
            'description' => $dto->description,
            'goal_amount' => $dto->goalAmount,
            'balance'     => 0,
        ]);
        return SavingMapper::toDTO($saving);
    }

    public function update(int $id, SavingDTO $dto): ?SavingDTO
    {
        $saving = $this->model->where('id', $id)->where('user_id', $dto->userId)->first();
        if (!$saving) return null;

        $saving->update([
            'name'        => $dto->name,
            'description' => $dto->description,
            'goal_amount' => $dto->goalAmount,
        ]);
        return SavingMapper::toDTO($saving->fresh());
    }

    public function delete(int $id, int $userId): bool
    {
        $saving = $this->model->where('id', $id)->where('user_id', $userId)->first();
        if (!$saving) return false;
        return $saving->delete();
    }

    public function deposit(int $id, int $userId, float $amount, ?string $description, string $date): SavingDTO
    {
        $saving = $this->model->where('id', $id)->where('user_id', $userId)->firstOrFail();

        $saving->increment('balance', $amount);

        $this->transactionModel->create([
            'savings_id'  => $saving->id,
            'user_id'     => $userId,
            'type'        => 'deposit',
            'amount'      => $amount,
            'description' => $description,
            'date'        => $date,
        ]);

        return SavingMapper::toDTO($saving->fresh());
    }

    public function withdraw(int $id, int $userId, float $amount, ?string $description, string $date): SavingDTO
    {
        $saving = $this->model->where('id', $id)->where('user_id', $userId)->firstOrFail();

        $saving->decrement('balance', $amount);

        $this->transactionModel->create([
            'savings_id'  => $saving->id,
            'user_id'     => $userId,
            'type'        => 'withdraw',
            'amount'      => $amount,
            'description' => $description,
            'date'        => $date,
        ]);

        return SavingMapper::toDTO($saving->fresh());
    }

    public function getHistory(int $id, int $userId): Collection
    {
        return $this->transactionModel
            ->where('savings_id', $id)
            ->where('user_id', $userId)
            ->orderByDesc('date')
            ->get()
            ->map(fn(SavingTransaction $t) => SavingTransactionMapper::toDTO($t));
    }

    public function getTotalBalance(int $userId): float
    {
        return (float) $this->model->where('user_id', $userId)->sum('balance');
    }
}
