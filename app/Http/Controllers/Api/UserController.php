<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserImageRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Mail\WelcomeUserMail;
use Illuminate\Support\Facades\Mail;

// @return \illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Retorna uma lista de usuários.
     *
     * Este método recupera uma lista de usuários do banco de dados
     * e a retorna como uma resposta JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        // Recupera os usuarios do banco de dados, ordenados por id em ordem decrescente
        $users = User::orderBy('id', 'DESC')->get();

        // Retorna os usuarios recuperados com uma resposta JSON
        return response()->json([
            'status' => true,
            'usuarios' => UserResource::collection($users),
        ], 200);
    }



     /**
     * Exibe os detalhes de um usuário específico.
     *
     * Este método retorna os detalhes de um usuário específico em formato JSON.
     *
     * @param  \App\Models\User  $id O objeto do usuário a ser exibido
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $id): JsonResponse
    {
        return response()->json([
            'status' => true,
            'user' => new UserResource($id),
        ], 200);
    }

    

    /**
     * Cria um novo usuário com os dados fornecidos na requisição.
     * 
     * @param  \App\Http\Requests\UserRequest  $request O objeto de requisição contendo os dados do usuário a ser criado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request) {
        
        // iniciar a transação
        DB::beginTransaction();

        try{
            $user = User::create([
                'username' => $request->username,
                'nome' => $request->nome,
                'telefone' => $request->telefone,
                'email' => $request->email,
                'password' => $request->password
            ]);

            // email de boas vindas
            Mail::to($user->email)->send(new WelcomeUserMail($user->nome));

            // operação é concluída com êxito
            DB::commit();

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => "Usuario cadastrado com sucesso!",
            ],201);

        }catch (Exception $e) {

            // operação não é concluída com êxito
            DB::rollBack();

            // retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => "Usuario não cadastrado",
                'error' => $e->getMessage(),
            ],400);
        }
    }


    
     /**
     * Atualizar os dados de um usuário existente com base nos dados fornecidos na requisição.
     * 
     * @param  \App\Http\Requests\UserRequest  $request O objeto de requisição contendo os dados do usuário a ser atualizado.
     * @param  \App\Models\User  $id O usuário a ser atualizado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, User $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Filtra apenas os campos presentes e não vazios na requisição
            $updateData = array_filter($request->only([
                'username',
                'nome',
                'telefone',
                'email',
                'password'
            ]), function ($value) {
                return $value !== null && $value !== '';
            });

            // Verifica se há dados para atualizar
            if (empty($updateData)) {
                return response()->json([
                    'status' => true,
                    'message' => "Nenhum dado válido fornecido para atualização.",
                    'user' => new UserResource($id)
                ], 200);
            }

            // Se existir password, criptografa
            if (isset($updateData['password'])) {
                $updateData['password'] = bcrypt($updateData['password']);
            }

            $id->update($updateData);

            DB::commit();

            return response()->json([
                'status' => true,
                'user' => new UserResource($id->fresh()),
                'message' => "Usuário atualizado com sucesso!",
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => "Usuário não atualizado",
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    

    /**
     * Excluir o usuário no banco de dados.
     * 
     * @param  \App\Models\User  $id O usuário a ser excluído.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $id) : JsonResponse
    {
        try {

            $id->delete();

            return response()->json([
                'status' => true,
                'user' => $id,
                'message' => "Usuario apagado com sucesso!",
            ],200);

        } catch (Exception $e) {

             // retorna uma mensagem de erro com status 400
             return response()->json([
                 'status' => false,
                 'message' => "Usuario não apagado",
                 'error' => $e->getMessage(),
             ],400);
        }
    }



    /**
     * Cadastrar ou atualizar uma imagem de um usuário existente com base nos dados fornecidos na requisição.
     * 
     * @param  \App\Http\Requests\UserImageRequest  $request O objeto de requisição contendo os dados da imagem a ser atualizada.
     * @param  \App\Models\User  $id O usuário a ser atualizado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function imagem(UserImageRequest $request, User $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            if (!$request->hasFile('imagem')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Nenhuma imagem enviada.',
                ], 400);
            }

            // se uma imagem já estiver cadastrada, ela será deletada
            if ($id->imagem && Storage::disk('public')->exists(str_replace('storage/', '', $id->imagem))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $id->imagem));
            }

            // se não armazena a nova imagem
            $path = $request->file('imagem')->store('usuarios', 'public');
            $id->imagem = 'storage/' . $path;
            $id->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Imagem salva com sucesso.',
                'user' => [
                    'user_id' => $id->id,
                    'imagem' => $id->imagem,
                ],
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao salvar imagem.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}