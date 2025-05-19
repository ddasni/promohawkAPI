<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Realiza o login do usuário e gera um token de autenticação.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // Validação dos campos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Busca o usuário
        $user = User::where('email', $request->email)->first();

        // Verifica credenciais
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais estão incorretas.'],
            ]);
        }

        // Cria token com Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login realizado com sucesso.',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Realiza o logout apagando o token atual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            /** @var PersonalAccessToken $token */
            $token = $request->user()->currentAccessToken();

            $token->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logout realizado com sucesso.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao realizar logout.',
            ], 400);
        }
    }

    /**
     * Retorna os dados do usuário autenticado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'status' => true,
            'user' => $request->user(),
        ], 200);
    }
}