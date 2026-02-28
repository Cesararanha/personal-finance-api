<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SummaryController extends Controller
{
    public function __construct(
        private readonly TransactionRepositoryInterface $repository
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $month = $request->query('month');

            if (! $month) {
                return response()->json(['message' => 'Month parameter is required.'], 422);
            }

            $parts = explode('-', $month);
            $year = (int) $parts[0];
            $monthNumber = (int) $parts[1];

            $transactions = $this->repository->findByMonth($userId, $monthNumber, $year);

            $income = $transactions
                ->filter(fn ($dto) => $dto->type === 'income')
                ->sum(fn ($dto) => $dto->amount);

            $expense = $transactions
                ->filter(fn ($dto) => $dto->type === 'expense')
                ->sum(fn ($dto) => $dto->amount);

            $balance = $income - $expense;

            return response()->json([
                'data' => [
                    'income' => $income,
                    'expense' => $expense,
                    'balance' => $balance,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Internal server error.'], 500);
        }
    }
}
