<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Mail\PasswordResetSuccessMail;
use Illuminate\Support\Facades\Mail;


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



    /**
     * recebe o e-mail do usuário como argumento e utiliza a classe 
     * Password do Laravel para enviar o link de redefinição de senha.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        Password::sendResetLink($request->only('email'));

        return response()->json(status: Response::HTTP_OK);
    }



    /**
     * recebe o token, o e-mail e a nova senha como argumentos e utiliza a 
     * classe Password do Laravel para redefinir a senha do usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();

                 // Enviando um e-mail para notificar a redefinição
                Mail::to($user->email)->send(new PasswordResetSuccessMail($user->name));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(status: Response::HTTP_OK);
        }

        return response()->json(['message' => 'Erro ao redefinir a senha'], status: Response::HTTP_BAD_REQUEST);
    }
}