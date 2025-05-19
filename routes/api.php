<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/users', [UserController::class, 'index']); //Metodo => GET | URL:()
// Route::get('/users/{id}', [UserController::class, 'show']); //Metodo => GET | URL:()
// Route::post('/users', [UserController::class, 'store']); //Metodo => POST | URL:()
// Route::put('/users/{id}', [UserController::class, 'update']); //Metodo => PUT | URL:()
// Route::delete('/users/{id}', [UserController::class, 'destroy']); //Metodo => DELETE | URL:()


// Rotas para gerenciamento de Login e Logout 
// O auth:sanctum é uma proteção de rota do Sanctum
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});


// Rotas para gerenciamento de Usuario
Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');                      // GET     /users
    Route::get('/users/{id}', 'show');                  // GET     /users/{id}
    Route::post('/users', 'store');                     // POST    /users
    Route::put('/users/{id}', 'update');                // PUT     /users/{id}
    Route::post('/users/{id}/imagem', 'updateImage');   // POST    /users/{id}/imagem
    Route::delete('/users/{id}', 'destroy');            // DELETE  /users/{id}
});


// Rotas para gerenciamento de Produtos
Route::controller(ProdutoController::class)->group(function () {
    Route::get('/produto', 'index');           // GET     /produto
    Route::get('/produto/{id}', 'show');       // GET     /produto/{id}
    Route::post('/produto', 'store');          // POST    /produto
    Route::put('/produto/{id}', 'update');     // PUT     /produto/{id}
    Route::delete('/produto/{id}', 'destroy'); // DELETE  /produto/{id}
});