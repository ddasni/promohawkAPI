<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LojaRequest;
use App\Http\Resources\LojaResource;
use App\Models\Loja;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LojaController extends Controller
{
    protected $cacheMinutes = 60;
    protected $cacheIndexKey = 'lojas_index';
    protected $cacheShowPrefix = 'loja_';

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
        $lojas = Cache::remember($this->cacheIndexKey, $this->cacheMinutes, function () {
            return Loja::with(['produtos', 'cupons'])->orderBy('id', 'DESC')->get();
        });

        // Retorna os lojas recuperados com uma resposta JSON
        return response()->json([
            'status' => true,
            'lojas' => LojaResource::collection($lojas),
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
        $cacheKey = $this->cacheShowPrefix . $id->id;
        
        $loja = Cache::remember($cacheKey, $this->cacheMinutes, function () use ($id) {
            return Loja::with(['produtos', 'cupons'])->findOrFail($id->id);
        });

        return response()->json([
            'status' => true,
            'loja' => new LojaResource($loja),
        ],200);
    }



    /**
    * Cria uma nova loja com os dados fornecidos na requisição.
    * 
    * @param  \App\Http\Requests\LojaRequest  $request O objeto de requisição contendo os dados do usuário a ser criado.
    * @return \Illuminate\Http\JsonResponse
    */
    public function store(LojaRequest $request)
    {
         // iniciar a transação
        DB::beginTransaction();

        try{
            $loja = Loja::create([
                'nome' => $request->nome,
                'imagem' => $request->imagem
            ]);

            // operação é concluída com êxito
            DB::commit();

            $this->clearCache();

            return response()->json([
                'status' => true,
                'loja' => new LojaResource($loja),
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
    public function update(LojaRequest $request, Loja $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $dados = $request->only(['nome', 'imagem']);

            $id->update($dados);

            DB::commit();
            $this->clearCache($id);

            return response()->json([
                'status' => true,
                'loja' => new LojaResource($id->fresh()),
                'message' => "Loja editada com sucesso!",
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => "Loja não foi editada.",
                'error' => $e->getMessage(),
            ], 400);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loja $id) : JsonResponse
    {
        try {

            $id->delete();

            $this->clearCache($id);

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

    
    
    protected function clearCache(Loja $loja = null)
    {
        Cache::forget($this->cacheIndexKey);
        
        if ($loja) {
            Cache::forget($this->cacheShowPrefix . $loja->id);
        }
    }
}
