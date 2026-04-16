<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Jobs\SendTransactionNotificationJob;
use App\Mappers\TransactionMapper;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionRepositoryInterface $repository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $month = $request->query('month');
            $categoryId = $request->query('category_id');
            $startDate = $request->query('start_date');
            $endDate = $request->query('end_date');
            $minAmount = $request->query('min_amount');
            $maxAmount = $request->query('max_amount');
            $sortBy = $request->query('sort_by', 'date');
            $order = $request->query('order', 'desc');

            $transactions = $this->repository->findFiltered(
                $userId, $month, $categoryId ? (int) $categoryId : null,
                $startDate, $endDate,
                $minAmount ? (float) $minAmount : null,
                $maxAmount ? (float) $maxAmount : null,
                $sortBy, $order
            );

            if ($transactions->isEmpty()) {
                return response()->json([
                    'message' => 'Nenhuma transação encontrada para os filtros selecionados.',
                    'data' => [],
                ], 200);
            }

            $data = $transactions->map(fn ($dto) => TransactionMapper::toArray($dto));

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $transaction = $this->repository->findById($id, $userId);
            if (! $transaction) {
                return response()->json([
                    'message' => 'Transação não encontrada.',
                ], 404);
            }
            $data = TransactionMapper::toArray($transaction);

            return response()->json([
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json([
                'message' => 'Ocorreu um erro interno. Tente novamente.',
            ], 500);
        }
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        try {

            $validated = $request->validated();
            $userId = $request->user()->id;

            if (! $this->repository->categoryExistsForUser((int) $validated['category_id'], $userId)) {
                return response()->json([
                    'message' => 'Categoria não encontrada.',
                ], 404);
            }
            $category = \App\Models\Category::find((int) $validated['category_id']);
            if (! $category->is_active) {
                return response()->json([
                    'message' => 'Não é possível criar transações em uma categoria arquivada.',
                ], 422);
            }
            $dto = TransactionMapper::fromRequest($validated, $userId);
            $transaction = $this->repository->create($dto);
            $data = TransactionMapper::toArray($transaction);

            $user = $request->user();
            SendTransactionNotificationJob::dispatch($data, $user->email, $user->name)
                ->onConnection('rabbitmq')
                ->onQueue('notifications');

            return response()->json([
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json([
                'message' => 'Ocorreu um erro interno. Tente novamente.',
            ], 500);
        }
    }

    public function update(UpdateTransactionRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $userId = $request->user()->id;

            // Busca transação atual para merge
            $existing = $this->repository->findById($id, $userId);
            if (! $existing) {
                return response()->json(['message' => 'Transação não encontrada.'], 404);
            }

            // Merge: usa o que veio no request, senão mantém o atual
            $merged = [
                'type' => $validated['type'] ?? $existing->type,
                'amount' => $validated['amount'] ?? $existing->amount,
                'description' => array_key_exists('description', $validated) ? $validated['description'] : $existing->description,
                'date' => $validated['date'] ?? $existing->date->format('Y-m-d'),
                'category_id' => $validated['category_id'] ?? $existing->categoryId,
            ];

            if (! $this->repository->categoryExistsForUser((int) $merged['category_id'], $userId)) {
                return response()->json(['message' => 'Categoria não encontrada.'], 404);
            }

            $dto = TransactionMapper::fromRequest($merged, $userId);
            $transaction = $this->repository->update($id, $dto);
            $data = TransactionMapper::toArray($transaction);

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->id;

            $deleted = $this->repository->delete($id, $userId);

            if (! $deleted) {
                return response()->json(['message' => 'Transação não encontrada.'], 404);
            }

            return response()->json([
                'message' => 'Transação excluída com sucesso.',
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }
}
