<?php

namespace App\Swagger;

class ReportSwagger
{
    /**
     * @OA\Post(
     *     path="/api/reports",
     *     summary="Solicita a geração assíncrona de um relatório (PDF ou CSV)",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"type"},
     *
     *             @OA\Property(property="type", type="string", enum={"pdf","csv"}, example="pdf"),
     *             @OA\Property(property="filters", type="object",
     *                 @OA\Property(property="month", type="string", example="2026-04"),
     *                 @OA\Property(property="start_date", type="string", example="2026-04-01"),
     *                 @OA\Property(property="end_date", type="string", example="2026-04-30"),
     *                 @OA\Property(property="category_id", type="integer", example=2)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=202,
     *         description="Relatório enfileirado. Um e-mail será enviado quando estiver pronto.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="type", type="string", example="pdf"),
     *                 @OA\Property(property="status", type="string", example="pending")
     *             ),
     *             @OA\Property(property="message", type="string", example="Relatório sendo gerado. Você receberá um e-mail quando estiver pronto.")
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
     *     path="/api/reports/{id}",
     *     summary="Consulta o status de um relatório",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Status do relatório",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="type", type="string", example="pdf"),
     *                 @OA\Property(property="status", type="string", enum={"pending","processing","done","failed"}, example="done"),
     *                 @OA\Property(property="filters", type="object"),
     *                 @OA\Property(property="created_at", type="string", example="2026-04-16 12:00:00")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response=404, description="Relatório não encontrado"),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function show() {}

    /**
     * @OA\Get(
     *     path="/api/reports/{id}/download",
     *     summary="Faz o download do arquivo gerado (requer autenticação)",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Arquivo PDF ou CSV para download"),
     *     @OA\Response(
     *         response=422,
     *         description="Relatório ainda não disponível",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Relatório ainda não está disponível."),
     *             @OA\Property(property="status", type="string", example="processing")
     *         )
     *     ),
     *
     *     @OA\Response(response=404, description="Relatório não encontrado"),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function download() {}
}
