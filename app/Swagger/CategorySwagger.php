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
     *     @OA\Parameter(
     *         name="archived",
     *         in="query",
     *         required=false,
     *         description="Se true, inclui categorias arquivadas",
     *
     *         @OA\Schema(type="string", enum={"true","false"}, example="true")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(
     *
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Alimentação"),
     *                     @OA\Property(property="is_active", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     ),
     *
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
     * @OA\Patch(
     *     path="/api/categories/{id}/archive",
     *     summary="Arquiva uma categoria (is_active = false)",
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
     *     @OA\Response(response=200, description="Categoria arquivada com sucesso"),
     *     @OA\Response(response=404, description="Categoria não encontrada"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function archive() {}

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Deleta uma categoria sem transações",
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
     *     @OA\Response(response=200, description="Categoria excluída com sucesso"),
     *     @OA\Response(response=404, description="Categoria não encontrada"),
     *     @OA\Response(response=409, description="Categoria possui transações vinculadas"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function destroy() {}
}
