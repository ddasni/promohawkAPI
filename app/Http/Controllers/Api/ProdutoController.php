<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    /**
    * Retorna uma lista de produtos.
    *
    * Este método recupera uma lista de produtos do banco de dados
    * e a retorna como uma resposta JSON.
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function index()
    {
        // Recupera os produtos do banco de dados, ordenados por id em ordem decrescente
        $produtos = Produto::orderBy('id', 'DESC')->get();

        // Retorna os produtos recuperados com uma resposta JSON
        return response()->json([
            'status' => true,
            'produtos' => $produtos,
        ],200);
    }



    /**
    * Exibe os detalhes de um produto específico.
    *
    * Este método retorna os detalhes de um produto específico em formato JSON.
    *
    * @param  \App\Models\Produto  $id O objeto do produto a ser exibido
    * @return \Illuminate\Http\JsonResponse
    */
    public function show(Produto $id)
    {
        return response()->json([
            'status' => true,
            'produto' => $id,
        ],200);
    }



    /**
    * Cria um novo produto com os dados fornecidos na requisição.
    * 
    * @param  \App\Http\Requests\ProdutoRequest  $request O objeto de requisição contendo os dados do usuário a ser criado.
    * @return \Illuminate\Http\JsonResponse
    */
    public function store(Request $request)
    {
         // iniciar a transação
        DB::beginTransaction();

        try{
            $produto = Produto::create([
                'produtoname' => $request->produtoname,
                'nome' => $request->nome,
                'telefone' => $request->telefone,
                'email' => $request->email,
                'password' => $request->password
            ]);

            // operação é concluída com êxito
            DB::commit();

            return response()->json([
                'status' => true,
                'produto' => $produto,
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
