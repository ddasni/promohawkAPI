<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Adm;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthAdmController extends Controller
{
    /**
     * Realiza o login do administrador e gera um token de autenticação.
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

        // Busca o administrador
        $adm = Adm::where('email', $request->email)->first();

        // Verifica credenciais
        if (! $adm || ! Hash::check($request->password, $adm->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais estão incorretas.'],
            ]);
        }

        // Cria token com Sanctum
        $token = $adm->createToken('auth_adm_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login de administrador realizado com sucesso.',
            'adm' => $adm,
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
            /** @var \Laravel\Sanctum\PersonalAccessToken $token */
            $token = $request->user()->currentAccessToken();

            $token->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logout de administrador realizado com sucesso.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao realizar logout de administrador.',
            ], 400);
        }
    }

    /**
     * Retorna os dados do administrador autenticado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): JsonResponse
    {
        $adm = auth()->user();
        
        return response()->json([
            'status' => true,
            'adm' => $adm,
        ], 200);
    }
}
