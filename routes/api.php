<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdmController;
use App\Http\Controllers\Api\AuthAdmController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LojaController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CupomController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\FavoritoController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\PrecoProdutoController;


// Rotas para gerenciamento de Login e Logout
// utilização do prefix para agrupar tudo em uma rota auth
// O auth:sanctum é uma proteção de rota do Sanctum
Route::prefix('auth')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/forgot-password', 'forgotPassword')->name('auth.forgot-password');
        Route::post('/reset-password', 'resetPassword')->name('auth.reset-password');
    });
    

    // Rotas protegidas do AuthController, onde é necessario do token
    Route::middleware('auth:sanctum')->controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/me', 'me');
    });
});


// Rotas para administradores
Route::prefix('adm')->group(function () {
    // Login
    Route::post('/login', [AuthAdmController::class, 'login']);
    
    // Rotas protegidas
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthAdmController::class, 'logout']); // Logout
        Route::get('/me', [AuthAdmController::class, 'me']); // exibir dados do adm logado
    });

    // Rotas para gerenciamento de adm
    Route::controller(AdmController::class)->group(function () {
        Route::get('/', 'index');           // GET     /adm
        Route::get('/{id}', 'show');       // GET     /adm/{id}
        Route::post('/', 'store');          // POST    /adm
        Route::put('/{id}', 'update');     // PUT     /adm/{id}
        Route::delete('/{id}', 'destroy'); // DELETE  /adm/{id}
    });
});


// Rotas para gerenciamento de Usuario
Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');            // GET     /users
    Route::get('/users/{id}', 'show');        // GET     /users/{id}
    Route::post('/users', 'store');           // POST    /users
    Route::put('/users/{id}', 'update');      // PUT     /users/{id}
    Route::delete('/users/{id}', 'destroy');  // DELETE  /users/{id}

    Route::post('/users/{id}/imagem', 'imagem');    // POST  /users/{id}/imagem
});


Route::middleware('auth:sanctum')->group(function () {
    // Rotas protegidas para gerenciamento da Lista de Desejos
    Route::controller(FavoritoController::class)->group(function () {
        Route::get('/favorito', 'index');              
        Route::get('/favorito/{id}', 'show');          
        Route::post('/favorito', 'store');             
        Route::put('/favorito/{id}', 'update');        
        Route::delete('/favorito/{id}', 'destroy');    

        // retorna os favoritos do usuário Logado e autenticado
        Route::get('/user-favoritos', 'userFavoritos');
    });
});


// Rotas para gerenciamento das reviews
Route::middleware('auth:sanctum')->group(function () {
    // Rotas protegidas para gerenciamento da Lista de Desejos
    Route::controller(ReviewController::class)->group(function () {
        Route::get('/review', 'index');              
        Route::get('/review/{id}', 'show');          
        Route::post('/review', 'store');             
        Route::put('/review/{id}', 'update');        
        Route::delete('/review/{id}', 'destroy');
    });
});


// Rotas para gerenciamento de Cupom
Route::controller(CupomController::class)->group(function () {
    Route::get('/cupom', 'index');           // GET     /cupom
    Route::get('/cupom/{id}', 'show');       // GET     /cupom/{id}
    Route::post('/cupom', 'store');          // POST    /cupom
    Route::post('/cupons', 'storeCupons');   // POST    /cupons
    Route::put('/cupom/{id}', 'update');     // PUT     /cupom/{id}
    Route::delete('/cupom/{id}', 'destroy'); // DELETE  /cupom/{id}
});


// Rotas para gerenciamento de Produtos
Route::controller(ProdutoController::class)->group(function () {
    Route::get('/produto/search', 'search');   // GET /produto/search?query=termo
    Route::get('/produto', 'index');           // GET     /produto
    Route::get('/produto/{id}', 'show');       // GET     /produto/{id}
    Route::post('/produto', 'store');          // POST    /produto
    Route::post('/produtos', 'storeProdutos'); // POST    /produtos
    Route::put('/produto/{id}', 'update');     // PUT     /produto/{id}
    Route::delete('/produto/{id}', 'destroy'); // DELETE  /produto/{id}
});


// Rotas para gerenciamento de Preços do Produtos
Route::controller(PrecoProdutoController::class)->group(function () {
    Route::get('/preco', 'index');           // GET     /preco
    Route::get('/preco/{id}', 'show');       // GET     /preco/{id}
    Route::post('/preco', 'store');          // POST    /preco
    Route::put('/preco/{id}', 'update');     // PUT     /preco/{id}
    Route::delete('/preco/{id}', 'destroy'); // DELETE  /preco/{id}
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