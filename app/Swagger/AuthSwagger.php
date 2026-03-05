<?php

namespace App\Swagger;

class AuthSwagger
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registra um novo usuário",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name","email","password","cpf","phone","birth_date"},
     *
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", example="joao@email.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="cpf", type="string", example="12345678901"),
     *             @OA\Property(property="phone", type="string", example="11999999999"),
     *             @OA\Property(property="birth_date", type="string", example="1990-01-01")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="1|abc123"),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="João Silva"),
     *                     @OA\Property(property="email", type="string", example="joao@email.com")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Dados inválidos"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function register() {}

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Autentica um usuário",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email","password"},
     *
     *             @OA\Property(property="email", type="string", example="joao@email.com"),
     *             @OA\Property(property="password", type="string", example="12345678")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Login realizado com sucesso",
     *
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="1|abc123"),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="João Silva"),
     *                     @OA\Property(property="email", type="string", example="joao@email.com")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="E-mail ou senha incorretos"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function login() {}

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Realiza logout do usuário",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(response=204, description="Logout realizado com sucesso"),
     *     @OA\Response(response=401, description="Não autenticado"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function logout() {}

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Retorna o usuário autenticado",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="João Silva"),
     *                 @OA\Property(property="email", type="string", example="joao@email.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Não autenticado"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function me() {}

    /**
     * @OA\Put(
     *     path="/api/me",
     *     summary="Atualiza perfil do usuário autenticado",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="João Atualizado"),
     *             @OA\Property(property="phone", type="string", example="11988888888"),
     *             @OA\Property(property="password", type="string", example="senhaAtual"),
     *             @OA\Property(property="new_password", type="string", example="novaSenha123"),
     *             @OA\Property(property="new_password_confirmation", type="string", example="novaSenha123")
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Perfil atualizado com sucesso"),
     *     @OA\Response(response=401, description="Não autenticado"),
     *     @OA\Response(response=422, description="Dados inválidos"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function update() {}
}
