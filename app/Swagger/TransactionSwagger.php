<?php

namespace App\Swagger;

class TransactionSwagger
{
    /**
     * @OA\Get(
     *     path="/api/transactions",
     *     summary="Lista transações com filtros",
     *     tags={"Transactions"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="month", in="query", required=false,
     *         @OA\Schema(type="string", example="2025-01")
     *     ),
     *     @OA\Parameter(name="category_id", in="query", required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(name="start_date", in="query", required=false,
     *         @OA\Schema(type="string", example="2025-01-01")
     *     ),
     *     @OA\Parameter(name="end_date", in="query", required=false,
     *         @OA\Schema(type="string", example="2025-01-31")
     *     ),
     *     @OA\Parameter(name="min_amount", in="query", required=false,
     *         @OA\Schema(type="number", example=50.00)
     *     ),
     *     @OA\Parameter(name="max_amount", in="query", required=false,
     *         @OA\Schema(type="number", example=500.00)
     *     ),
     *     @OA\Parameter(name="sort_by", in="query", required=false,
     *         @OA\Schema(type="string", enum={"date","amount","description"}, example="date")
     *     ),
     *     @OA\Parameter(name="order", in="query", required=false,
     *         @OA\Schema(type="string", enum={"asc","desc"}, example="desc")
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
     *     @OA\Parameter(name="id", in="path", required=true,
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
     *             @OA\Property(property="type", type="string", enum={"expense"}, example="expense"),
     *             @OA\Property(property="amount", type="number", example=150.00),
     *             @OA\Property(property="description", type="string", example="Mercado"),
     *             @OA\Property(property="date", type="string", example="2025-01-15"),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *
     *     @OA\Response(response=201, description="Transação criada com sucesso"),
     *     @OA\Response(response=404, description="Categoria não encontrada"),
     *     @OA\Response(response=422, description="Dados inválidos ou categoria arquivada"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function store() {}

    /**
     * @OA\Put(
     *     path="/api/transactions/{id}",
     *     summary="Atualiza uma transação (suporta update parcial)",
     *     tags={"Transactions"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             @OA\Property(property="type", type="string", enum={"expense"}, example="expense"),
     *             @OA\Property(property="amount", type="number", example=200.00),
     *             @OA\Property(property="description", type="string", example="Mercado e Feira"),
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
     *     @OA\Parameter(name="id", in="path", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="Transação excluída com sucesso"),
     *     @OA\Response(response=404, description="Transação não encontrada"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function destroy() {}
}
