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
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\LojaRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProdutoRequest;
use App\Http\Requests\CategoriaRequest;
use App\Http\Resources\ProdutoResource;


class ProdutoController extends Controller
{
    // Tempo de vida do cache em minutos
    protected $cacheMinutes = 60;
    
    // Chave para cache da lista de produtos
    protected $cacheIndexKey = 'produtos_index';
    
    // Prefixo para cache de produto individual
    protected $cacheShowPrefix = 'produto_';


    /**
    * Realiza busca de produtos em tempo real conforme o usuário digita.
    * Retorna apenas nome e imagem principal do produto para exibição durante a pesquisa.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function search(Request $request)
    {
        $searchTerm = trim($request->input('query', ''));

        if (strlen($searchTerm) < 2) {
            return response()->json([
                'status' => true,
                'produtos' => [],
            ]);
        }

        $produtos = Produto::select('id', 'nome')
            ->with(['imagens', 'precos', 'reviews'])
            ->withAvg('reviews as media_nota', 'avaliacao_produto')
            ->whereRaw("MATCH(nome) AGAINST(? IN NATURAL LANGUAGE MODE)", [$searchTerm])
            ->limit(10)
            ->get();

        $resultados = $produtos->map(function ($produto) {
            return [
                'id' => $produto->id,
                'nome' => $produto->nome,
                'imagem' => $produto->imagens->first()->imagem ?? null,
                'media_nota' => $produto->media_nota ? number_format($produto->media_nota, 1) : null,
                'preco' => $produto->precos->first()->preco ?? null,
            ];
        });

        return response()->json([
            'status' => true,
            'produtos' => $resultados,
        ]);
    }



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
        $produtos = Cache::remember($this->cacheIndexKey, $this->cacheMinutes, function () {
            return Produto::with([
                'precos.loja',
                'imagens',
                'reviews.usuario',
                'categoria',
            ])->orderBy('id', 'desc')->get();
        });

        return response()->json([
            'status' => true,
            'produtos' => ProdutoResource::collection($produtos),
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
        $cacheKey = $this->cacheShowPrefix . $id->id;
        
        $produto = Cache::remember($cacheKey, $this->cacheMinutes, function () use ($id) {
            return Produto::with([
                'precos.loja',
                'imagens',
                'reviews.usuario',
                'categoria',
            ])->findOrFail($id->id);
        });

        return response()->json([
            'status' => true,
            'produto' => new ProdutoResource($produto),
        ], 200);
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

        // aumentando temporariamente o limite de memória
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
            $produto = Produto::where('nome', $request->nome)->first();


            $produtoFoiCriado = false;

            if (!$produto) {
                // Cria o produto se não existir
                $produto = Produto::create([
                    'nome' => $request->nome,
                    'descricao' => $request->descricao ?? null,
                    'categoria_id' => $categoria->id,
                    'link' => $request->link,
                    'status_produto' => $request->status_produto ?? 'ativo'
                ]);

                $produtoFoiCriado = true;
            } 
            else {
                // Se o produto já existe, atualiza o link quando vem um novo preço
                $produto->update([
                    'link' => $request->link
                ]);
            }

            // Salvar imagens adicionais apenas se o produto for novo
            if ($produtoFoiCriado && $request->has('imagens') && is_array($request->imagens)) {
                foreach ($request->imagens as $img) {
                    ImagemProduto::create([
                        'produto_id' => $produto->id,
                        'imagem' => $img
                    ]);
                }
            }


            // Registrar o preço do produto
            $preco = PrecoProduto::create([
                'produto_id' => $produto->id,
                'loja_id' => $loja->id,
                'preco' => $request->preco,
                'forma_pagamento' => $request->forma_pagamento ?? null,
                'parcelas' => $request->parcelas ?? null,
                'valor_parcela' => $request->valor_parcela ?? null,
            ]);

            DB::commit();

            // Limpa o cache de produtos
            $this->clearCache();

            return response()->json([
                'status' => true,
                'message' => $produtoFoiCriado
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
        DB::beginTransaction();

        try {
            // Atualiza os campos básicos do produto se estiverem presentes na requisição
            $id->update($request->only([
                'nome', 
                'descricao', 
                'link', 
                'status_produto',
                'categoria_id'
            ]));

            // Atualizar imagens se estiverem presentes na requisição
            if ($request->has('imagens')) {
                // Primeiro, deletar todas as imagens existentes
                $id->imagens()->delete();
                
                // Depois, adicionar as novas imagens
                foreach ($request->imagens as $img) {
                    ImagemProduto::create([
                        'produto_id' => $id->id,
                        'imagem' => $img
                    ]);
                }
            }

            DB::commit();

            // Limpa o cache do produto atualizado e da lista
            $this->clearCache($id);

            return response()->json([
                'status' => true,
                'produto' => new ProdutoResource($id->fresh()),
                'message' => "Produto editado com sucesso!",
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => "Produto não editado",
                'error' => $e->getMessage(),
            ], 400);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produto $id) : JsonResponse
    {
        try {

            $id->delete();

            $this->clearCache($id);

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


    /**
     * Limpa o cache relacionado aos produtos
     * 
     * @param Produto|null $produto Produto específico para limpar (opcional)
     */
    protected function clearCache(Produto $produto = null)
    {
        // Limpa a lista de produtos
        Cache::forget($this->cacheIndexKey);
        
        // Limpa o cache do produto específico se fornecido
        if ($produto) {
            Cache::forget($this->cacheShowPrefix . $produto->id);
        }
    }
}