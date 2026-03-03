<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIncomeRequest;
use App\Mappers\MonthlyIncomeMapper;
use App\Repositories\Interfaces\MonthlyIncomeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IncomeController extends Controller
{
    public function __construct(
        private readonly MonthlyIncomeRepositoryInterface $repository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $month = $request->query('month');

            $incomes = $this->repository->findByUser($userId, $month);

            if ($incomes->isEmpty()) {
                return response()->json([
                    'message' => 'Nenhuma receita registrada para o período selecionado.',
                    'data' => [],
                ], 200);
            }

            $data = $incomes->map(fn ($dto) => MonthlyIncomeMapper::toArray($dto));

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function store(StoreIncomeRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $userId = $request->user()->id;

            $dto = MonthlyIncomeMapper::fromRequest($validated, $userId);
            $income = $this->repository->create($dto);

            return response()->json([
                'data' => MonthlyIncomeMapper::toArray($income),
            ], 201);
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
                return response()->json(['message' => 'Receita não encontrada.'], 404);
            }

            return response()->json(['message' => 'Receita excluída com sucesso.'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }
}
