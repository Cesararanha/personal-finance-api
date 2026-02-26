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
     *
     *         @OA\Schema(type="string", example="2025-01")
     *     ),
     *
     *     @OA\Response(response=200, description="Sucesso"),
     *     @OA\Response(response=422, description="Parâmetro month obrigatório"),
     *     @OA\Response(response=401, description="Não autenticado"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function index() {}
}
