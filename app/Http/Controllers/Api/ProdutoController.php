<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Loja;
use App\Models\Produto;
use App\Models\Categoria;
use App\Models\PrecoProduto;
use Illuminate\Http\Request;
use App\Models\ImagemProduto;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LojaRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProdutoRequest;
use App\Http\Requests\CategoriaRequest;
use App\Http\Resources\ProdutoResource;


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
        $produtos = Produto::with([
            'precos.loja' => function ($query) {
                $query->orderBy('created_at', 'asc'); // histórico organizado
            },
            'imagens',
            'reviews'
        ])
        ->orderBy('id', 'DESC')
        ->get()
        ->map(function ($produto) {
            $produto->media_nota = round($produto->reviews->avg('nota'), 1);
            unset($produto->reviews); // mantendo só a media, sem reviews
            return $produto;
        });

        return response()->json([
            'status' => true,
            'produtos' => $produtos,
        ], 200);
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
        $produto = Produto::with([
            'precos.loja' => function ($query) {
                $query->orderBy('created_at', 'asc');
            },
            'imagens',
            'loja',
            'reviews.usuario'
        ])->findOrFail($id->id);

        return response()->json([
            'status' => true,
            'produto' => new ProdutoResource($produto),
        ]);
    }



    /**
    * Cria um novo produto com os dados fornecidos na requisição.
    * 
    * @param  \App\Http\Requests\ProdutoRequest  $request O objeto de requisição contendo os dados do usuário a ser criado.
    * @return \Illuminate\Http\JsonResponse
    */
    public function store(ProdutoRequest $request)
    {  
        // Validando as partes de loja e categoria
        app(LojaRequest::class)->validateResolved();
        app(CategoriaRequest::class)->validateResolved();

        // aumentando temporariamente o limite de memoria
        ini_set('memory_limit', '1024M');

        DB::beginTransaction();

        try {
            // Cria ou busca loja
            $loja = Loja::firstOrCreate(
                ['nome' => $request->loja_nome],
                ['imagem' => $request->loja_imagem]
            );


            // Cria ou busca categoria
            $categoria = Categoria::firstOrCreate(
                ['nome' => $request->categoria_nome],
                ['imagem' => $request->categoria_imagem]
            );


            // Verificando se o produto já existe pelo nome e pela loja
            $produto = Produto::where('nome', $request->nome)
                ->where('loja_id', $loja->id)
                ->first();

                
            if (!$produto) {
                // Cria o produto se não existir
                $produto = Produto::create([
                    'nome' => $request->nome,
                    'descricao' => $request->descricao ?? null,
                    'categoria_id' => $categoria->id,
                    'loja_id' => $loja->id,
                    'link' => $request->link,
                    'status_produto' => $request->status_produto ?? 'ativo'
                ]);
            }

            // Salvar imagens adicionais se enviadas como array
            if ($request->has('imagens') && is_array($request->imagens)) {
                foreach ($request->imagens as $img) {
                    ImagemProduto::create([
                        'produto_id' => $produto->id,
                        'imagem' => $img
                    ]);
                }
            }

            // Registrando o preço do produto
            $preco = PrecoProduto::create([
                'produto_id' => $produto->id,
                'loja_id' => $loja->id,
                'preco' => $request->preco,
                'forma_pagamento' => $request->forma_pagamento,
                'parcelas' => $request->parcelas,
                'valor_parcela' => $request->valor_parcela
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => $produto->wasRecentlyCreated
                    ? 'Produto cadastrado com sucesso!'
                    : 'Preço registrado para produto existente!',
                'produto' => $produto,
                'loja' => $loja,
                'categoria' => $categoria,
                'preco' => $preco,
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao cadastrar ou registrar o preço.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ProdutoRequest $request, Produto $id) : JsonResponse
    {
        // iniciar a transação
        DB::beginTransaction();

        try {         
            $id->update($request->validated());

            // operação é concluída com êxito
            DB::commit();

            return response()->json([
                'status' => true,
                'produto' => $id,
                'message' => "Produto editado com sucesso!",
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
    public function destroy(Produto $id) : JsonResponse
    {
        try {

            $id->delete();

            return response()->json([
                'status' => true,
                'produto' => $id,
                'message' => "Produto apagado com sucesso!",
            ],200);

        } catch (Exception $e) {

            // retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => "Produto não apagado",
                'error' => $e->getMessage(),
            ],400);
        }
    }
}