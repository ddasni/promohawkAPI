<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function index():JsonResponse
    {
        // Recupera os usuarios do banco de dados, ordenados por id em ordem decrescente
        $users = User::orderBy('id', 'DESC')->get();

        // Retorna os usuarios recuperados com uma resposta JSON
        return response()->json([
            'status' => true,
            'usuarios' => $users,
        ],200);
    }


     /**
     * Exibe os detalhes de um usuário específico.
     *
     * Este método retorna os detalhes de um usuário específico em formato JSON.
     *
     * @param  \App\Models\User  $id O objeto do usuário a ser exibido
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $id):JsonResponse
    {
        return response()->json([
            'status' => true,
            'user' => $id,
        ],200);
    }

    
    /**
     * Cria um novo usuário com os dados fornecidos na requisição.
     * 
     * @param  \App\Http\Requests\UserRequest  $request O objeto de requisição contendo os dados do usuário a ser criado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request) {
        // criar novo usuário
        
        // iniciar a transação
        DB::beginTransaction();

        try{
            $user = User::create([
                'name'  => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

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
    public function update(UserRequest $request, User $id) : JsonResponse
    {
        // atualizar usuário

        // iniciar a transação
        DB::beginTransaction();

        try { 
            $id->update([
                'name'  => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            // operação é concluída com êxito
            DB::commit();

            return response()->json([
                'status' => true,
                'user' => $id,
                'message' => "Usuario editado com sucesso!",
            ],200);

        }catch (Exception $e){
            // operação não é concluída com êxito
            DB::rollBack();

            // retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => "Usuario não editado",
            ],400);
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
        // deletar usuário
        
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
             ],400);
        }
    }
}
