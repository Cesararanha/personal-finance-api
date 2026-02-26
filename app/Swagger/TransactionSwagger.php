<?php

namespace App\Swagger;

class TransactionSwagger
{
    /**
     * @OA\Get(
     *     path="/api/transactions",
     *     summary="Lista todas as transações do usuário",
     *     tags={"Transactions"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="month",
     *         in="query",
     *         required=false,
     *
     *         @OA\Schema(type="string", example="2025-01")
     *     ),
     *
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         required=false,
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(response=200, description="Sucesso"),
     *     @OA\Response(response=401, description="Não autenticado"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function index() {}

    /**
     * @OA\Get(
     *     path="/api/transactions/{id}",
     *     summary="Busca uma transação por ID",
     *     tags={"Transactions"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="Sucesso"),
     *     @OA\Response(response=404, description="Transação não encontrada"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function show() {}

    /**
     * @OA\Post(
     *     path="/api/transactions",
     *     summary="Cria uma nova transação",
     *     tags={"Transactions"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"type","amount","date","category_id"},
     *
     *             @OA\Property(property="type", type="string", enum={"income","expense"}, example="income"),
     *             @OA\Property(property="amount", type="number", example=100.50),
     *             @OA\Property(property="description", type="string", example="Salário"),
     *             @OA\Property(property="date", type="string", example="2025-01-01"),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *
     *     @OA\Response(response=201, description="Transação criada com sucesso"),
     *     @OA\Response(response=404, description="Categoria não encontrada"),
     *     @OA\Response(response=422, description="Dados inválidos"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function store() {}

    /**
     * @OA\Put(
     *     path="/api/transactions/{id}",
     *     summary="Atualiza uma transação",
     *     tags={"Transactions"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"type","amount","date","category_id"},
     *
     *             @OA\Property(property="type", type="string", enum={"income","expense"}, example="expense"),
     *             @OA\Property(property="amount", type="number", example=50.00),
     *             @OA\Property(property="description", type="string", example="Mercado"),
     *             @OA\Property(property="date", type="string", example="2025-01-15"),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Transação atualizada com sucesso"),
     *     @OA\Response(response=404, description="Transação ou categoria não encontrada"),
     *     @OA\Response(response=422, description="Dados inválidos"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *     path="/api/transactions/{id}",
     *     summary="Deleta uma transação",
     *     tags={"Transactions"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=204, description="Transação deletada com sucesso"),
     *     @OA\Response(response=404, description="Transação não encontrada"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function destroy() {}
}
