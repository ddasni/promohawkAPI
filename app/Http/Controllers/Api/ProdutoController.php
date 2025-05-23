<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
