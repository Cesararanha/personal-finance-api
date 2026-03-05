<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavingMovementRequest;
use App\Http\Requests\StoreSavingRequest;
use App\Http\Requests\UpdateSavingRequest;
use App\Mappers\SavingMapper;
use App\Mappers\SavingTransactionMapper;
use App\Repositories\Interfaces\SavingRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SavingController extends Controller
{
    public function __construct(
        private readonly SavingRepositoryInterface $repository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $savings = $this->repository->findAll($userId);

            if ($savings->isEmpty()) {
                return response()->json([
                    'message' => 'Você ainda não possui caixinhas criadas.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'data' => $savings->map(fn ($dto) => SavingMapper::toArray($dto)),
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $saving = $this->repository->findById($id, $userId);

            if (! $saving) {
                return response()->json(['message' => 'Caixinha não encontrada.'], 404);
            }

            return response()->json(['data' => SavingMapper::toArray($saving)], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function store(StoreSavingRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $userId = $request->user()->id;

            $dto = SavingMapper::fromRequest($validated, $userId);
            $saving = $this->repository->create($dto);

            return response()->json(['data' => SavingMapper::toArray($saving)], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function update(UpdateSavingRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $userId = $request->user()->id;

            $existing = $this->repository->findById($id, $userId);
            if (! $existing) {
                return response()->json(['message' => 'Caixinha não encontrada.'], 404);
            }

            $merged = [
                'name' => $validated['name'] ?? $existing->name,
                'description' => array_key_exists('description', $validated) ? $validated['description'] : $existing->description,
                'goal_amount' => array_key_exists('goal_amount', $validated) ? $validated['goal_amount'] : $existing->goalAmount,
            ];

            $dto = SavingMapper::fromRequest($merged, $userId);
            $saving = $this->repository->update($id, $dto);

            return response()->json(['data' => SavingMapper::toArray($saving)], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $saving = $this->repository->findById($id, $userId);

            if (! $saving) {
                return response()->json(['message' => 'Caixinha não encontrada.'], 404);
            }

            if ($saving->balance > 0) {
                return response()->json([
                    'message' => 'Você precisa retirar todo o saldo antes de excluir esta caixinha.',
                ], 409);
            }

            $this->repository->delete($id, $userId);

            return response()->json(['message' => 'Caixinha excluída com sucesso.'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function deposit(SavingMovementRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $userId = $request->user()->id;

            $saving = $this->repository->findById($id, $userId);
            if (! $saving) {
                return response()->json(['message' => 'Caixinha não encontrada.'], 404);
            }

            $updated = $this->repository->deposit(
                $id,
                $userId,
                (float) $validated['amount'],
                $validated['description'] ?? null,
                $validated['date']
            );

            return response()->json(['data' => SavingMapper::toArray($updated)], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function withdraw(SavingMovementRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $userId = $request->user()->id;

            $saving = $this->repository->findById($id, $userId);
            if (! $saving) {
                return response()->json(['message' => 'Caixinha não encontrada.'], 404);
            }

            if ($saving->balance < (float) $validated['amount']) {
                return response()->json([
                    'message' => 'Saldo insuficiente. Saldo disponível: R$ '.number_format($saving->balance, 2, ',', '.').'.',
                ], 422);
            }

            $updated = $this->repository->withdraw(
                $id,
                $userId,
                (float) $validated['amount'],
                $validated['description'] ?? null,
                $validated['date']
            );

            return response()->json(['data' => SavingMapper::toArray($updated)], 200);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function history(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->id;

            $saving = $this->repository->findById($id, $userId);
            if (! $saving) {
                return response()->json(['message' => 'Caixinha não encontrada.'], 404);
            }

            $history = $this->repository->getHistory($id, $userId);

            if ($history->isEmpty()) {
                return response()->json([
                    'message' => 'Nenhuma movimentação encontrada para esta caixinha.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'data' => $history->map(fn ($dto) => SavingTransactionMapper::toArray($dto)),
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }
}
