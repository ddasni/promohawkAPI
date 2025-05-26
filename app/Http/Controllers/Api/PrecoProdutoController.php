<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrecoProdutoRequest;
use App\Models\PrecoProduto;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrecoProdutoController extends Controller
{
    /**
     * Retorna uma lista de Precos dos Produtos.
     *
     * Este método recupera uma lista de produtos do banco de dados
     * e a retorna como uma resposta JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index():JsonResponse
    {
        // Recupera os precos dos produtos do banco de dados, ordenados por id em ordem decrescente
        $precos = PrecoProduto::orderBy('id', 'DESC')->get();

        // Retorna os preços de produtos recuperados com uma resposta JSON
        return response()->json([
            'status' => true,
            'Precos dos Produtos' => $precos,
        ],200);
    }



     /**
     * Exibe os detalhes de preço de um produto específico.
     *
     * Este método retorna os detalhes de um usuário específico em formato JSON.
     *
     * @param  \App\Models\PrecoProduto  $id O objeto do usuário a ser exibido
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(PrecoProduto $id):JsonResponse
    {
        return response()->json([
            'status' => true,
            'Precos do Produto' => $id,
        ],200);
    }

    

    /**
     * Cria um novo usuário com os dados fornecidos na requisição.
     * 
     * @param  \App\Http\Requests\PrecoProdutoRequest $request O objeto de requisição contendo os dados do usuário a ser criado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PrecoProdutoRequest $request) {
        
        // iniciar a transação
        DB::beginTransaction();

        try{
            $user = PrecoProduto::create([
                'username' => $request->username,
                'nome' => $request->nome,
                'telefone' => $request->telefone,
                'email' => $request->email,
                'password' => $request->password
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
                'error' => $e->getMessage(),
            ],400);
        }
    }


    
     /**
     * Atualizar os dados de um usuário existente com base nos dados fornecidos na requisição.
     * 
     * @param  \App\Http\Requests\PrecoProdutoRequest  $request O objeto de requisição contendo os dados do usuário a ser atualizado.
     * @param  \App\Models\PrecoProduto  $id O usuário a ser atualizado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PrecoProdutoRequest $request, PrecoProduto $id) : JsonResponse
    {
        // iniciar a transação
        DB::beginTransaction();

        try {         
            $id->update([
                'username' => $request->username,
                'nome' => $request->nome,
                'telefone' => $request->telefone,
                'email' => $request->email,
                'password' => $request->password
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
                'error' => $e->getMessage(),
            ],400);
        }
    }

    

    /**
     * Excluir o usuário no banco de dados.
     * 
     * @param  \App\Models\PrecoProduto  $id O usuário a ser excluído.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PrecoProduto $id) : JsonResponse
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
}
