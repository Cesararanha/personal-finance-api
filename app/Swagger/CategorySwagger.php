<?php

namespace App\Swagger;

class CategorySwagger
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Lista todas as categorias do usuário",
     *     tags={"Categories"},
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
     *     path="/api/categories/{id}",
     *     summary="Busca uma categoria por ID",
     *     tags={"Categories"},
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
     *     @OA\Response(response=404, description="Categoria não encontrada"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function show() {}

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Cria uma nova categoria",
     *     tags={"Categories"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name"},
     *
     *             @OA\Property(property="name", type="string", example="Alimentação")
     *         )
     *     ),
     *
     *     @OA\Response(response=201, description="Categoria criada com sucesso"),
     *     @OA\Response(response=422, description="Dados inválidos"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function store() {}

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Atualiza uma categoria",
     *     tags={"Categories"},
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
     *             required={"name"},
     *
     *             @OA\Property(property="name", type="string", example="Transporte")
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Categoria atualizada com sucesso"),
     *     @OA\Response(response=404, description="Categoria não encontrada"),
     *     @OA\Response(response=422, description="Dados inválidos"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Deleta uma categoria",
     *     tags={"Categories"},
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
     *     @OA\Response(response=200, description="Categoria deletada com sucesso"),
     *     @OA\Response(response=404, description="Categoria não encontrada"),
     *     @OA\Response(response=409, description="Categoria possui transações vinculadas"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function destroy() {}
}
