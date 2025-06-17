<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrecoProdutoRequest;
use App\Models\PrecoProduto;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PrecoProdutoController extends Controller
{
    /**
     * Retorna uma lista de preços dos produtos.
     */
    public function index(): JsonResponse
    {
        $precos = PrecoProduto::with('loja')->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => true,
            'precos' => PrecoProdutoResource::collection($precos),
        ], 200);
    }

    /**
     * Exibe os detalhes de um preço específico.
     */
    public function show(PrecoProduto $id): JsonResponse
    {
        $id->load('loja'); // eager load

        return response()->json([
            'status' => true,
            'preco' => new PrecoProdutoResource($id),
        ], 200);
    }

    /**
     * Cadastra um novo preço de produto.
     */
    public function store(PrecoProdutoRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $preco = PrecoProduto::create([
                'produto_id' => $request->produto_id,
                'loja_id' => $request->loja_id,
                'preco' => $request->preco,
                'forma_pagamento' => $request->forma_pagamento,
                'parcelas' => $request->parcelas,
                'valor_parcela' => $request->valor_parcela,
                'data_registro' => now(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'preco' => $preco,
                'message' => 'Preço cadastrado com sucesso!',
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao cadastrar o preço.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Atualiza os dados de um preço existente.
     */
    public function update(PrecoProdutoRequest $request, PrecoProduto $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $id->update([
                'produto_id' => $request->produto_id ?? $id->produto_id,
                'loja_id' => $request->loja_id ?? $id->loja_id,
                'preco' => $request->preco ?? $id->preco,
                'forma_pagamento' => $request->forma_pagamento ?? $id->forma_pagamento,
                'parcelas' => $request->parcelas ?? $id->parcelas,
                'valor_parcela' => $request->valor_parcela ?? $id->valor_parcela,
                'data_registro' => now(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'preco' => $id,
                'message' => 'Preço atualizado com sucesso!',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar o preço.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove um preço do banco de dados.
     */
    public function destroy(PrecoProduto $id): JsonResponse
    {
        try {
            $id->delete();

            return response()->json([
                'status' => true,
                'message' => 'Preço removido com sucesso!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao remover o preço.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}