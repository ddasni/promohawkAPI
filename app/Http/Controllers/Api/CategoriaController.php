<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriaResource;
use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function index(): JsonResponse
    {
        // Carrega as categorias
        $categorias = Categoria::orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => true,
            'categorias' => CategoriaResource::collection($categorias),
        ]);
    }

    public function show(Categoria $id): JsonResponse
    {
        // Carrega os produtos da categoria específica
        $id->load('produtos');

        return response()->json([
            'status' => true,
            'categoria' => new CategoriaResource($id),
        ]);
    }

    public function store(CategoriaRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $categoria = Categoria::create($request->validated());

            DB::commit();

            return response()->json([
                'status' => true,
                'categoria' => $categoria,
                'message' => 'Categoria cadastrada com sucesso!',
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Categoria não cadastrada.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(CategoriaRequest $request, Categoria $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $id->update($request->validated());

            DB::commit();

            return response()->json([
                'status' => true,
                'categoria' => $id,
                'message' => 'Categoria atualizada com sucesso!',
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Categoria não atualizada.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy(Categoria $id): JsonResponse
    {
        try {
            $id->delete();

            return response()->json([
                'status' => true,
                'message' => 'Categoria deletada com sucesso!',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao deletar categoria.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
