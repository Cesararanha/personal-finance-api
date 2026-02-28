<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Mappers\TransactionMapper;
use App\Models\Category;
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
            if ($month) {
                $parts = explode('-', $month);
                $year = $parts[0];
                $month = $parts[1];
                $transactions = $this->repository->findByMonth($userId, (int) $month, (int) $year);
            } elseif ($categoryId) {
                $transactions = $this->repository->findByCategory((int) $categoryId, $userId);
            } else {
                $transactions = $this->repository->findAll($userId);
            }
            $data = $transactions->map(fn ($dto) => TransactionMapper::toArray($dto));

            return response()->json([
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json([
                'message' => 'Internal server error.',
            ], 500);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $transaction = $this->repository->findById($id, $userId);
            if (! $transaction) {
                return response()->json([
                    'message' => 'Transaction not found.',
                ], 404);
            }
            $data = TransactionMapper::toArray($transaction);

            return response()->json([
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json([
                'message' => 'Internal server error.',
            ], 500);
        }
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        try {

            $validated = $request->validated();
            $userId = $request->user()->id;
            $category = Category::where('id', $validated['category_id'])
                ->where('user_id', $userId)
                ->first();
            if (! $category) {
                return response()->json([
                    'message' => 'Category not found.',
                ], 404);
            }
            $dto = TransactionMapper::fromRequest($validated, $userId);
            $transaction = $this->repository->create($dto);
            $data = TransactionMapper::toArray($transaction);

            return response()->json([
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json([
                'message' => 'Internal server error.',
            ], 500);
        }
    }

    public function update(UpdateTransactionRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $userId = $request->user()->id;
            $category = Category::where('id', $validated['category_id'])
                ->where('user_id', $userId)
                ->first();
            if (! $category) {
                return response()->json([
                    'message' => 'Category not found.',
                ], 404);
            }
            $dto = TransactionMapper::fromRequest($validated, $userId);
            $transaction = $this->repository->update($id, $dto);
            if (! $transaction) {
                return response()->json([
                    'message' => 'Transaction not found.',
                ], 404);
            }
            $data = TransactionMapper::toArray($transaction);

            return response()->json([
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json([
                'message' => 'Internal server error.',
            ], 500);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->id;

            $deleted = $this->repository->delete($id, $userId);

            if (! $deleted) {
                return response()->json(['message' => 'Transaction not found.'], 404);
            }

            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Internal server error.'], 500);
        }
    }
}
