<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LojaRequest;
use App\Models\Loja;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LojaController extends Controller
{
    /**
    * Retorna uma lista de lojas.
    *
    * Este método recupera uma lista de lojas do banco de dados
    * e a retorna como uma resposta JSON.
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function index()
    {
        // Recupera os lojas do banco de dados, ordenados por id em ordem decrescente
        $lojas = Loja::with(['produtos', 'cupons'])->orderBy('id', 'DESC')->get();

        // Retorna os lojas recuperados com uma resposta JSON
        return response()->json([
            'status' => true,
            'lojas' => $lojas,
        ],200);
    }



    /**
    * Exibe os detalhes de um loja específico.
    *
    * Este método retorna os detalhes de um loja específico em formato JSON.
    *
    * @param  \App\Models\Loja  $id O objeto do loja a ser exibido
    * @return \Illuminate\Http\JsonResponse
    */
    public function show(Loja $id)
    {
        $id->load(['produtos', 'cupons']);

        return response()->json([
            'status' => true,
            'loja' => $id,
        ],200);
    }



    /**
    * Cria uma nova loja com os dados fornecidos na requisição.
    * 
    * @param  \App\Http\Requests\LojaRequest  $request O objeto de requisição contendo os dados do usuário a ser criado.
    * @return \Illuminate\Http\JsonResponse
    */
    public function store(Request $request)
    {
         // iniciar a transação
        DB::beginTransaction();

        try{
            $loja = Loja::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'imagem' => $request->imagem,
                'link' => $request->link
            ]);

            // operação é concluída com êxito
            DB::commit();

            return response()->json([
                'status' => true,
                'loja' => $loja,
                'message' => "Loja cadastrada com sucesso!",
            ],201);

        }catch (Exception $e) {

            // operação não é concluída com êxito
            DB::rollBack();

            // retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => "Loja não cadastrada",
                'error' => $e->getMessage(),
            ],400);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(LojaRequest $request, Loja $id) : JsonResponse
    {
        // iniciar a transação
        DB::beginTransaction();

        try {         
            $id->update([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'imagem' => $request->imagem,
                'link' => $request->link
            ]);

            // operação é concluída com êxito
            DB::commit();

            return response()->json([
                'status' => true,
                'Loja' => $id,
                'message' => "Loja editado com sucesso!",
            ],200);

        }catch (Exception $e){
            // operação não é concluída com êxito
            DB::rollBack();

            // retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => "Loja não editado",
                'error' => $e->getMessage(),
            ],400);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loja $id) : JsonResponse
    {
        try {

            $id->delete();

            return response()->json([
                'status' => true,
                'Loja' => $id,
                'message' => "Loja apagado com sucesso!",
            ],200);

        } catch (Exception $e) {

            // retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => "Loja não apagado",
                'error' => $e->getMessage(),
            ],400);
        }
    }
}
