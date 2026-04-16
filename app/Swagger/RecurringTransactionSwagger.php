<?php

namespace App\Swagger;

class RecurringTransactionSwagger
{
    /**
     * @OA\Get(
     *     path="/api/recurring-transactions",
     *     summary="Lista todas as transações recorrentes do usuário",
     *     tags={"Recurring Transactions"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de transações recorrentes",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(
     *
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="description", type="string", example="Assinatura Netflix"),
     *                     @OA\Property(property="amount", type="number", example=39.90),
     *                     @OA\Property(property="frequency", type="string", enum={"daily","weekly","monthly"}, example="monthly"),
     *                     @OA\Property(property="next_due_date", type="string", example="2026-05-15"),
     *                     @OA\Property(property="is_active", type="boolean", example=true),
     *                     @OA\Property(property="category_id", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/api/recurring-transactions",
     *     summary="Cria uma transação recorrente",
     *     tags={"Recurring Transactions"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"category_id","description","amount","frequency","start_date"},
     *
     *             @OA\Property(property="category_id", type="integer", example=2),
     *             @OA\Property(property="description", type="string", example="Assinatura Netflix"),
     *             @OA\Property(property="amount", type="number", example=39.90),
     *             @OA\Property(property="frequency", type="string", enum={"daily","weekly","monthly"}, example="monthly"),
     *             @OA\Property(property="start_date", type="string", example="2026-05-15")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Transação recorrente criada",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="description", type="string", example="Assinatura Netflix"),
     *                 @OA\Property(property="amount", type="number", example=39.90),
     *                 @OA\Property(property="frequency", type="string", example="monthly"),
     *                 @OA\Property(property="next_due_date", type="string", example="2026-05-15"),
     *                 @OA\Property(property="is_active", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response=422, description="Dados inválidos"),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function store() {}

    /**
     * @OA\Get(
     *     path="/api/recurring-transactions/{id}",
     *     summary="Exibe uma transação recorrente",
     *     tags={"Recurring Transactions"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Transação recorrente encontrada"),
     *     @OA\Response(response=404, description="Não encontrada"),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function show() {}

    /**
     * @OA\Put(
     *     path="/api/recurring-transactions/{id}",
     *     summary="Atualiza uma transação recorrente",
     *     tags={"Recurring Transactions"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="description", type="string", example="Assinatura Netflix HD"),
     *             @OA\Property(property="amount", type="number", example=55.90),
     *             @OA\Property(property="frequency", type="string", enum={"daily","weekly","monthly"}, example="monthly"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Transação recorrente atualizada"),
     *     @OA\Response(response=404, description="Não encontrada"),
     *     @OA\Response(response=422, description="Dados inválidos"),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *     path="/api/recurring-transactions/{id}",
     *     summary="Remove uma transação recorrente",
     *     tags={"Recurring Transactions"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Removida com sucesso"),
     *     @OA\Response(response=404, description="Não encontrada"),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function destroy() {}
}
