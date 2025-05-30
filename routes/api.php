<?php

use App\Http\Controllers\Api\AdmController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\CupomController;
use App\Http\Controllers\Api\LojaController;
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
// utilização do prefix para agrupar tudo em uma rota auth
// O auth:sanctum é uma proteção de rota do Sanctum
Route::prefix('auth')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/forgot-password', 'forgotPassword')->name('auth.forgot-password');
        Route::post('/reset-password', 'resetPassword')->name('auth.reset-password');
    });
    

    // Rotas protegidas do AuthController
    Route::middleware('auth:sanctum')->controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/me', 'me');
    });
});


// Rotas para gerenciamento de adm
Route::controller(AdmController::class)->group(function () {
    Route::get('/adm', 'index');           // GET     /adm
    Route::get('/adm/{id}', 'show');       // GET     /adm/{id}
    Route::post('/adm', 'store');          // POST    /adm
    Route::put('/adm/{id}', 'update');     // PUT     /adm/{id}
    Route::delete('/adm/{id}', 'destroy'); // DELETE  /adm/{id}
});


// Rotas para gerenciamento de Usuario
Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');            // GET     /users
    Route::get('/users/{id}', 'show');        // GET     /users/{id}
    Route::post('/users', 'store');           // POST    /users
    Route::put('/users/{id}', 'update');      // PUT     /users/{id}
    Route::delete('/users/{id}', 'destroy');  // DELETE  /users/{id}

    Route::post('/users/{id}/addImage', 'createImage');    // POST  /users/{id}/addImagem
    Route::post('/users/{id}/editImage', 'updateImage');  // POST  /users/{id}/updateImagem
});


// Rotas para gerenciamento de Cupom
Route::controller(CupomController::class)->group(function () {
    Route::get('/cupom', 'index');           // GET     /cupom
    Route::get('/cupom/{id}', 'show');       // GET     /cupom/{id}
    Route::post('/cupom', 'store');          // POST    /cupom
    Route::put('/cupom/{id}', 'update');     // PUT     /cupom/{id}
    Route::delete('/cupom/{id}', 'destroy'); // DELETE  /cupom/{id}
});


// Rotas para gerenciamento de Produtos
Route::controller(ProdutoController::class)->group(function () {
    Route::get('/produto', 'index');           // GET     /produto
    Route::get('/produto/{id}', 'show');       // GET     /produto/{id}
    Route::post('/produto', 'store');          // POST    /produto
    Route::put('/produto/{id}', 'update');     // PUT     /produto/{id}
    Route::delete('/produto/{id}', 'destroy'); // DELETE  /produto/{id}
});

    
// Rotas para gerenciamento de Lojas
Route::controller(LojaController::class)->group(function () {
    Route::get('/loja', 'index');           // GET     /loja
    Route::get('/loja/{id}', 'show');       // GET     /loja/{id}
    Route::post('/loja', 'store');          // POST    /loja
    Route::put('/loja/{id}', 'update');     // PUT     /loja/{id}
    Route::delete('/loja/{id}', 'destroy'); // DELETE  /loja/{id}
});


// Rotas para gerenciamento de Categorias
Route::controller(CategoriaController::class)->group(function () {
    Route::get('/categoria', 'index');           // GET     /categoria
    Route::get('/categoria/{id}', 'show');       // GET     /categoria/{id}
    Route::post('/categoria', 'store');          // POST    /categoria
    Route::put('/categoria/{id}', 'update');     // PUT     /categoria/{id}
    Route::delete('/categoria/{id}', 'destroy'); // DELETE  /categoria/{id}
});