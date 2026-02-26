<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Personal Finance API",
 *     version="1.0.0",
 *     description="API para gerenciamento de finanças pessoais"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller {}
