<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\MonthlyIncomeRepositoryInterface;
use App\Repositories\Interfaces\SavingRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SummaryController extends Controller
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly MonthlyIncomeRepositoryInterface $incomeRepository,
        private readonly SavingRepositoryInterface $savingRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $month = $request->query('month');

            if (! $month) {
                return response()->json([
                    'message' => 'O parâmetro mês é obrigatório. Exemplo: ?month=2025-01',
                ], 422);
            }

            // Busca receitas do mês
            $incomes = $this->incomeRepository->findByUser($userId, $month);
            $totalIncome = $incomes->sum(fn ($dto) => $dto->amount);

            // Busca despesas do mês
            $transactions = $this->transactionRepository->findFiltered($userId, $month);
            $totalExpenses = $transactions
                ->filter(fn ($dto) => $dto->type === 'expense')
                ->sum(fn ($dto) => $dto->amount);

            // Saldo e caixinhas
            $balance = $totalIncome - $totalExpenses;
            $savingsBalance = $this->savingRepository->getTotalBalance($userId);
            $availableBalance = $balance - $savingsBalance;

            // Breakdown por categoria
            $byCategory = $transactions
                ->filter(fn ($dto) => $dto->type === 'expense')
                ->groupBy(fn ($dto) => $dto->categoryId)
                ->map(function ($group) use ($totalExpenses) {
                    $total = $group->sum(fn ($dto) => $dto->amount);
                    $first = $group->first();

                    return [
                        'category' => $first->categoryName ?? 'Sem categoria',
                        'total' => round($total, 2),
                        'percentage' => $totalExpenses > 0
                            ? round(($total / $totalExpenses) * 100, 1)
                            : 0,
                        'transactions_count' => $group->count(),
                    ];
                })
                ->values();

            return response()->json([
                'data' => [
                    'period' => $month,
                    'total_income' => round($totalIncome, 2),
                    'total_expenses' => round($totalExpenses, 2),
                    'balance' => round($balance, 2),
                    'savings_balance' => round($savingsBalance, 2),
                    'available_balance' => round($availableBalance, 2),
                    'by_category' => $byCategory,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }
}
