<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Mappers\UserMapper;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $dto = UserMapper::fromRequest($validated);
            $createUser = $this->userRepository->create($dto);
            $userModel = User::find($createUser->id);
            $token = $userModel->createToken('auth_token')->plainTextToken;

            return response()->json([
                'data' => [
                    'token' => $token,
                    'user' => UserMapper::toArray($createUser),
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json([
                'message' => 'Ocorreu um erro interno. Tente novamente.',
            ], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            if (! Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
                return response()->json(['message' => 'E-mail ou senha incorretos.'], 401);
            }
            $user = $this->userRepository->findByEmail($validated['email']);
            $userModel = User::find($user->id);
            $token = $userModel->createToken('auth_token')->plainTextToken;

            return response()->json([
                'data' => [
                    'token' => $token,
                    'user' => UserMapper::toArray($user),
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json([
                'message' => 'Ocorreu um erro interno. Tente novamente.',
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json([
                'message' => 'Ocorreu um erro interno. Tente novamente.',
            ], 500);
        }
    }

    public function me(Request $request): JsonResponse
    {
        try {
            $dto = UserMapper::toDTO($request->user());

            return response()->json(['data' => UserMapper::toArray($dto)], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json([
                'message' => 'Ocorreu um erro interno. Tente novamente.',
            ], 500);
        }
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $user = $request->user();
            if (isset($validated['password'])) {
                if (! password_verify($validated['password'], $user->password)) {
                    return response()->json(['message' => 'Senha atual incorreta.'], 400);
                }
                $validated['password'] = $validated['new_password'];
            }

            unset($validated['new_password']);
            $user->update($validated);

            $dto = UserMapper::toDTO($user->fresh());

            return response()->json(['data' => UserMapper::toArray($dto)], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json([
                'message' => 'Ocorreu um erro interno.',
            ], 500);
        }
    }
}
