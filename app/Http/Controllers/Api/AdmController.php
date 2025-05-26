<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdmRequest;
use App\Models\Adm;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdmController extends Controller
{
    public function index(): JsonResponse
    {
        $adms = Adm::orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => true,
            'adms' => $adms,
        ]);
    }

    public function show(Adm $id): JsonResponse
    {
        return response()->json([
            'status' => true,
            'adm' => $id,
        ]);
    }

    public function store(AdmRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $adm = Adm::create($request->validated());

            DB::commit();

            return response()->json([
                'status' => true,
                'adm' => $adm,
                'message' => 'Administrador cadastrado com sucesso!',
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Administrador não cadastrado.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(AdmRequest $request, Adm $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $id->update($request->validated());

            DB::commit();

            return response()->json([
                'status' => true,
                'adm' => $id,
                'message' => 'Administrador atualizado com sucesso!',
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Administrador não atualizado.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy(Adm $id): JsonResponse
    {
        try {
            $id->delete();

            return response()->json([
                'status' => true,
                'message' => 'Administrador deletado com sucesso!',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao deletar administrador.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
