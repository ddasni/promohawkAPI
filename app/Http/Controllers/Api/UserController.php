<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// @return \illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index():JsonResponse
    {
        // Recupera os usuarios do banco de dados, ordenados por id em ordem decrescente
        $users = User::orderBy('id', 'DESC')->get();

        // Retorna os usuarios recuperados com uma resposta JSON
        return response()->json([
            'status' => true,
            'usuarios' => $users,
        ],200);
    }
    public function show(User $user):JsonResponse
    {
        return response()->json([
            'status' => true,
            'user' => $user,
        ],200);
    }
}
