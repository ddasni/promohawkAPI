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


    public function show(User $id):JsonResponse
    {
        return response()->json([
            'status' => true,
            'user' => $id,
        ],200);
    }
    
    
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
