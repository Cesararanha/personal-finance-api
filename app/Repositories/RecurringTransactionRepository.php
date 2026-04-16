<?php

namespace App\Repositories;

use App\DTOs\RecurringTransactionDTO;
use App\Mappers\RecurringTransactionMapper;
use App\Models\RecurringTransaction;
use App\Repositories\Interfaces\RecurringTransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RecurringTransactionRepository implements RecurringTransactionRepositoryInterface
{
    public function __construct(private readonly RecurringTransaction $model) {}

    public function findAll(int $userId): Collection
    {
        return $this->model
            ->with('category')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($m) => RecurringTransactionMapper::toDTO($m));
    }

    public function findById(int $id, int $userId): ?RecurringTransactionDTO
    {
        $model = $this->model
            ->with('category')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        return $model ? RecurringTransactionMapper::toDTO($model) : null;
    }

    public function findDue(): Collection
    {
        return $this->model
            ->with(['category', 'user'])
            ->where('is_active', true)
            ->where('next_due_date', '<=', Carbon::today())
            ->get()
            ->map(fn ($m) => RecurringTransactionMapper::toDTO($m));
    }

    public function create(RecurringTransactionDTO $dto): RecurringTransactionDTO
    {
        $model = $this->model->create([
            'user_id' => $dto->userId,
            'category_id' => $dto->categoryId,
            'description' => $dto->description,
            'amount' => $dto->amount,
            'frequency' => $dto->frequency,
            'next_due_date' => $dto->nextDueDate,
            'is_active' => $dto->isActive,
        ]);

        return RecurringTransactionMapper::toDTO($model->load('category'));
    }

    public function update(int $id, int $userId, array $data): ?RecurringTransactionDTO
    {
        $model = $this->model->where('id', $id)->where('user_id', $userId)->first();
        if (! $model) {
            return null;
        }

        $model->update($data);

        return RecurringTransactionMapper::toDTO($model->fresh()->load('category'));
    }

    public function delete(int $id, int $userId): bool
    {
        $model = $this->model->where('id', $id)->where('user_id', $userId)->first();
        if (! $model) {
            return false;
        }

        return $model->delete();
    }
}
