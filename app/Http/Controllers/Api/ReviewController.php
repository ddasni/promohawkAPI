<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    public function index(): JsonResponse
    {
        $reviews = Review::with(['produto', 'usuario'])->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => true,
            'reviews' => ReviewResource::collection($reviews),
        ], 200);
    }

    public function show(Review $id): JsonResponse
    {
        $review = Review::with(['produto', 'usuario'])->findOrFail($id->id);

        return response()->json([
            'status' => true,
            'review' => new ReviewResource($review),
        ], 200);
    }

    public function store(ReviewRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $review = Review::create($request->validated());

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Review cadastrada com sucesso!',
                'review' => $review,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao cadastrar review.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(ReviewRequest $request, Review $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $id->update($request->validated());

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Review atualizada com sucesso!',
                'review' => $id,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar review.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy(Review $id): JsonResponse
    {
        try {
            $id->delete();

            return response()->json([
                'status' => true,
                'message' => 'Review deletada com sucesso!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao deletar review.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function listarPorProduto($produtoId)
    {
        $reviews = Review::where('produto_id', $produtoId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'reviews' => $reviews,
        ], 200);
    }

}