<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CupomRequest;
use App\Models\Cupom;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CupomController extends Controller
{
    /**
    * Retorna uma lista de cupons.
    *
    * Este método recupera uma lista de cupons do banco de dados
    * e a retorna como uma resposta JSON.
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function index()
    {
        // Recupera os cupons do banco de dados, ordenados por id em ordem decrescente
        $cupons = Cupom::orderBy('id', 'DESC')->get();

        // Retorna os cupons recuperados com uma resposta JSON
        return response()->json([
            'status' => true,
            'cupons' => $cupons,
        ],200);
    }



    /**
    * Exibe os detalhes de um cupom específico.
    *
    * Este método retorna os detalhes de um cupom específico em formato JSON.
    *
    * @param  \App\Models\Cupom $id O objeto do cupom a ser exibido
    * @return \Illuminate\Http\JsonResponse
    */
    public function show(Cupom $id)
    {
        return response()->json([
            'status' => true,
            'cupom' => $id,
        ],200);
    }



    /**
    * Cria um novo cupom com os dados fornecidos na requisição.
    * 
    * @param  \App\Http\Requests\CupomRequest  $request O objeto de requisição contendo os dados do usuário a ser criado.
    * @return \Illuminate\Http\JsonResponse
    */
    public function store(Request $request)
    {
         // iniciar a transação
        DB::beginTransaction();

        try{
            $cupom = Cupom::create([
                'codigo' => $request->codigo,
                'desconto' => $request->desconto,
                'validade' => $request->imagem
            ]);

            // operação é concluída com êxito
            DB::commit();

            return response()->json([
                'status' => true,
                'cupom' => $cupom,
                'message' => "Cupom cadastrado com sucesso!",
            ],201);

        }catch (Exception $e) {

            // operação não é concluída com êxito
            DB::rollBack();

            // retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => "Produto não cadastrado",
                'error' => $e->getMessage(),
            ],400);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(CupomRequest $request, Cupom $id) : JsonResponse
    {
        // iniciar a transação
        DB::beginTransaction();

        try {         
            $id->update([
                'codigo' => $request->codigo,
                'desconto' => $request->desconto,
                'validade' => $request->imagem
            ]);

            // operação é concluída com êxito
            DB::commit();

            return response()->json([
                'status' => true,
                'cupom' => $id,
                'message' => "Cupom editado com sucesso!",
            ],200);

        }catch (Exception $e){
            // operação não é concluída com êxito
            DB::rollBack();

            // retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => "Produto não editado",
                'error' => $e->getMessage(),
            ],400);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cupom $id) : JsonResponse
    {
        try {

            $id->delete();

            return response()->json([
                'status' => true,
                'cupom' => $id,
                'message' => "Cupom apagado com sucesso!",
            ],200);

        } catch (Exception $e) {

            // retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => "Cupom não apagado",
                'error' => $e->getMessage(),
            ],400);
        }
    }
}
