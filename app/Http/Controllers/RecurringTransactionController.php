<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecurringTransactionRequest;
use App\Http\Requests\UpdateRecurringTransactionRequest;
use App\Mappers\RecurringTransactionMapper;
use App\Models\Category;
use App\Repositories\Interfaces\RecurringTransactionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RecurringTransactionController extends Controller
{
    public function __construct(
        private readonly RecurringTransactionRepositoryInterface $repository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $items = $this->repository->findAll($request->user()->id);

            if ($items->isEmpty()) {
                return response()->json(['message' => 'Nenhuma transação recorrente encontrada.', 'data' => []], 200);
            }

            return response()->json(['data' => $items->map(fn ($dto) => RecurringTransactionMapper::toArray($dto))], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $dto = $this->repository->findById($id, $request->user()->id);
            if (! $dto) {
                return response()->json(['message' => 'Transação recorrente não encontrada.'], 404);
            }

            return response()->json(['data' => RecurringTransactionMapper::toArray($dto)], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function store(StoreRecurringTransactionRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $userId = $request->user()->id;

            $category = Category::where('id', $validated['category_id'])
                ->where('user_id', $userId)
                ->first();

            if (! $category) {
                return response()->json(['message' => 'Categoria não encontrada.'], 404);
            }

            if (! $category->is_active) {
                return response()->json(['message' => 'Não é possível usar uma categoria arquivada.'], 422);
            }

            $dto = RecurringTransactionMapper::fromRequest($validated, $userId);
            $created = $this->repository->create($dto);

            return response()->json(['data' => RecurringTransactionMapper::toArray($created)], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function update(UpdateRecurringTransactionRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $userId = $request->user()->id;

            if (isset($validated['category_id'])) {
                $category = Category::where('id', $validated['category_id'])
                    ->where('user_id', $userId)
                    ->first();

                if (! $category) {
                    return response()->json(['message' => 'Categoria não encontrada.'], 404);
                }
            }

            $updated = $this->repository->update($id, $userId, $validated);
            if (! $updated) {
                return response()->json(['message' => 'Transação recorrente não encontrada.'], 404);
            }

            return response()->json(['data' => RecurringTransactionMapper::toArray($updated)], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $deleted = $this->repository->delete($id, $request->user()->id);
            if (! $deleted) {
                return response()->json(['message' => 'Transação recorrente não encontrada.'], 404);
            }

            return response()->json(['message' => 'Transação recorrente excluída com sucesso.'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }
}
