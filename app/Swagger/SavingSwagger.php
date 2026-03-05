<?php

namespace App\Swagger;

class SavingSwagger
{
    /**
     * @OA\Get(
     *     path="/api/savings",
     *     summary="Lista todas as caixinhas do usuário",
     *     tags={"Savings"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(response=200, description="Sucesso"),
     *     @OA\Response(response=401, description="Não autenticado"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function index() {}

    /**
     * @OA\Get(
     *     path="/api/savings/{id}",
     *     summary="Busca uma caixinha por ID",
     *     tags={"Savings"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="Sucesso"),
     *     @OA\Response(response=404, description="Caixinha não encontrada"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function show() {}

    /**
     * @OA\Post(
     *     path="/api/savings",
     *     summary="Cria uma nova caixinha",
     *     tags={"Savings"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name"},
     *
     *             @OA\Property(property="name", type="string", example="Reserva de Emergência"),
     *             @OA\Property(property="description", type="string", example="6 meses de despesas"),
     *             @OA\Property(property="goal_amount", type="number", example=10000.00)
     *         )
     *     ),
     *
     *     @OA\Response(response=201, description="Caixinha criada com sucesso"),
     *     @OA\Response(response=422, description="Dados inválidos"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function store() {}

    /**
     * @OA\Put(
     *     path="/api/savings/{id}",
     *     summary="Atualiza uma caixinha",
     *     tags={"Savings"},
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
     *             @OA\Property(property="name", type="string", example="Reserva Atualizada"),
     *             @OA\Property(property="description", type="string", example="Nova descrição"),
     *             @OA\Property(property="goal_amount", type="number", example=15000.00)
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Caixinha atualizada com sucesso"),
     *     @OA\Response(response=404, description="Caixinha não encontrada"),
     *     @OA\Response(response=422, description="Dados inválidos"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *     path="/api/savings/{id}",
     *     summary="Deleta uma caixinha com saldo zero",
     *     tags={"Savings"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="Caixinha excluída com sucesso"),
     *     @OA\Response(response=404, description="Caixinha não encontrada"),
     *     @OA\Response(response=409, description="Caixinha possui saldo e não pode ser excluída"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function destroy() {}

    /**
     * @OA\Post(
     *     path="/api/savings/{id}/deposit",
     *     summary="Deposita um valor na caixinha",
     *     tags={"Savings"},
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
     *             required={"amount","date"},
     *
     *             @OA\Property(property="amount", type="number", example=500.00),
     *             @OA\Property(property="description", type="string", example="Primeiro depósito"),
     *             @OA\Property(property="date", type="string", example="2025-01-10")
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Depósito realizado com sucesso"),
     *     @OA\Response(response=404, description="Caixinha não encontrada"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function deposit() {}

    /**
     * @OA\Post(
     *     path="/api/savings/{id}/withdraw",
     *     summary="Retira um valor da caixinha",
     *     tags={"Savings"},
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
     *             required={"amount","date"},
     *
     *             @OA\Property(property="amount", type="number", example=100.00),
     *             @OA\Property(property="description", type="string", example="Retirada parcial"),
     *             @OA\Property(property="date", type="string", example="2025-01-20")
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Retirada realizada com sucesso"),
     *     @OA\Response(response=404, description="Caixinha não encontrada"),
     *     @OA\Response(response=422, description="Saldo insuficiente"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function withdraw() {}

    /**
     * @OA\Get(
     *     path="/api/savings/{id}/history",
     *     summary="Retorna o histórico de movimentações da caixinha",
     *     tags={"Savings"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="savings_id", type="integer", example=1),
     *                     @OA\Property(property="type", type="string", enum={"deposit","withdraw"}, example="deposit"),
     *                     @OA\Property(property="amount", type="number", example=500.00),
     *                     @OA\Property(property="description", type="string", example="Primeiro depósito"),
     *                     @OA\Property(property="date", type="string", example="2025-01-10")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Caixinha não encontrada"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function history() {}
}
