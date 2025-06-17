<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavoritoRequest;
use App\Http\Resources\FavoritoResource;
use App\Models\Favorito;
use App\Models\Produto;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoritoController extends Controller
{
    public function index(): JsonResponse
    {
        $favoritos = Favorito::with(['usuario', 'produto'])->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => true,
            'favoritos' => FavoritoResource::collection($favoritos)
        ], 200);
    }

    public function show(Favorito $id): JsonResponse
    {
        return response()->json([
            'status' => true,
            'favorito' => new FavoritoResource($id)
        ], 200);
    }

    public function userFavoritos(Request $request): JsonResponse
    {
        $usuario = $request->user();

        $favoritos = Favorito::where('usuario_id', $usuario->id)
            ->with(['produto.precos.loja'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'favoritos' => FavoritoResource::collection($favoritos)
        ]);
    }


    public function store(FavoritoRequest $request): JsonResponse
    {
        try {
            $favorito = Favorito::firstOrCreate([
                'usuario_id' => $request->usuario_id,
                'produto_id' => $request->produto_id,
            ]);

            return response()->json([
                'status' => true,
                'message' => $favorito->wasRecentlyCreated ? 'Favorito adicionado com sucesso!' : 'Favorito já existe!',
                'favorito' => $favorito
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao adicionar favorito.',
                'error' => $e->getMessage()
            ], 400);
        }
    }


    public function update(FavoritoRequest $request, Favorito $id): JsonResponse
    {
        try {
            $id->update([
                'produto_id' => $request->produto_id,
                'usuario_id' => $request->usuario_id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Favorito atualizado com sucesso.',
                'favorito' => $id->load(['usuario', 'produto'])
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar favorito.',
                'error' => $e->getMessage()
            ], 400);
        }
    }


    public function destroy(Favorito $id): JsonResponse
    {
        try {
            $id->delete();

            return response()->json([
                'status' => true,
                'message' => 'Favorito removido com sucesso.',
                'favorito' => $id
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao remover favorito.',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}