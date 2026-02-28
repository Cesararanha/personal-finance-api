<?php

namespace App\Repositories;

use App\DTOs\TransactionDTO;
use App\Mappers\TransactionMapper;
use App\Models\Category;
use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\Collection;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function __construct(private readonly Transaction $model) {}

    public function findAll(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get()->map(fn (Transaction $transaction) => TransactionMapper::toDTO($transaction));
    }

    public function findById(int $id, int $userId): ?TransactionDTO
    {
        $transaction = $this->model->where('id', $id)->where('user_id', $userId)->first();

        return $transaction ? TransactionMapper::toDTO($transaction) : null;
    }

    public function findByMonth(int $userId, int $month, int $year): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->map(fn (Transaction $transaction) => TransactionMapper::toDTO($transaction));
    }

    public function findByCategory(int $categoryId, int $userId): Collection
    {
        return $this->model
            ->where('category_id', $categoryId)
            ->where('user_id', $userId)
            ->get()
            ->map(fn (Transaction $transaction) => TransactionMapper::toDTO($transaction));
    }

    public function create(TransactionDTO $dto): TransactionDTO
    {
        $transaction = $this->model->create([
            'amount' => $dto->amount,
            'date' => $dto->date,
            'description' => $dto->description,
            'category_id' => $dto->categoryId,
            'user_id' => $dto->userId,
            'type' => $dto->type,
        ]);

        return TransactionMapper::toDTO($transaction);
    }

    public function update(int $id, TransactionDTO $dto): ?TransactionDTO
    {
        $transaction = $this->model->where('id', $id)->where('user_id', $dto->userId)->first();
        if (! $transaction) {
            return null;
        }

        $transaction->update([
            'amount' => $dto->amount,
            'date' => $dto->date,
            'description' => $dto->description,
            'category_id' => $dto->categoryId,
            'type' => $dto->type,
        ]);

        return TransactionMapper::toDTO($transaction->fresh());
    }

    public function delete(int $id, int $userId): bool
    {
        $transaction = $this->model->where('id', $id)->where('user_id', $userId)->first();
        if (! $transaction) {
            return false;
        }

        return $transaction->delete();
    }

    public function categoryExistsForUser(int $categoryId, int $userId): bool
    {
        return Category::query()
            ->where('id', $categoryId)
            ->where('user_id', $userId)
            ->exists();
    }

    public function findFiltered(int $userId, ?string $month = null, ?int $categoryId = null): Collection
    {
        $query = $this->model->newQuery()
            ->where('user_id', $userId);

        if (! is_null($categoryId)) {
            $query->where('category_id', $categoryId);
        }
        if (! is_null($month)) {
            try {
                $start = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                $end = (clone $start)->endOfMonth();
                $query->whereBetween('date', [$start, $end]);
            } catch (\Exception $e) {
                return collect();
            }
        }

        return $query
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Transaction $transaction) => TransactionMapper::toDTO($transaction));
    }
}
