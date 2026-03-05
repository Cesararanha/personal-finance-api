<?php

namespace App\Swagger;

class SummarySwagger
{
    /**
     * @OA\Get(
     *     path="/api/summary",
     *     summary="Retorna o resumo financeiro mensal",
     *     tags={"Summary"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="month",
     *         in="query",
     *         required=true,
     *         description="Mês no formato YYYY-MM",
     *
     *         @OA\Schema(type="string", example="2025-01")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="period", type="string", example="2025-01"),
     *                 @OA\Property(property="total_income", type="number", example=5000.00),
     *                 @OA\Property(property="total_expenses", type="number", example=1500.00),
     *                 @OA\Property(property="balance", type="number", example=3500.00),
     *                 @OA\Property(property="savings_balance", type="number", example=500.00),
     *                 @OA\Property(property="available_balance", type="number", example=3000.00),
     *                 @OA\Property(property="by_category", type="array",
     *
     *                     @OA\Items(
     *
     *                         @OA\Property(property="category", type="string", example="Alimentação"),
     *                         @OA\Property(property="total", type="number", example=800.00),
     *                         @OA\Property(property="percentage", type="number", example=53.3),
     *                         @OA\Property(property="transactions_count", type="integer", example=5)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response=422, description="Parâmetro month obrigatório"),
     *     @OA\Response(response=401, description="Não autenticado"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function index() {}
}
