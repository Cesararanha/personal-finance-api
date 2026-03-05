<?php

namespace App\Swagger;

class IncomeSwagger
{
    /**
     * @OA\Get(
     *     path="/api/incomes",
     *     summary="Lista as receitas do usuário",
     *     tags={"Incomes"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="month",
     *         in="query",
     *         required=false,
     *         description="Filtra por mês no formato YYYY-MM",
     *
     *         @OA\Schema(type="string", example="2025-01")
     *     ),
     *
     *     @OA\Response(response=200, description="Sucesso"),
     *     @OA\Response(response=401, description="Não autenticado"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/api/incomes",
     *     summary="Registra uma nova receita",
     *     tags={"Incomes"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"amount","received_at"},
     *
     *             @OA\Property(property="amount", type="number", example=5000.00),
     *             @OA\Property(property="description", type="string", example="Salário Janeiro"),
     *             @OA\Property(property="received_at", type="string", example="2025-01-05")
     *         )
     *     ),
     *
     *     @OA\Response(response=201, description="Receita registrada com sucesso"),
     *     @OA\Response(response=422, description="Dados inválidos"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function store() {}

    /**
     * @OA\Delete(
     *     path="/api/incomes/{id}",
     *     summary="Remove uma receita",
     *     tags={"Incomes"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="Receita excluída com sucesso"),
     *     @OA\Response(response=404, description="Receita não encontrada"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function destroy() {}
}
