<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/users', [UserController::class, 'index']); //Metodo => GET | URL:()
// Route::get('/users/{id}', [UserController::class, 'show']); //Metodo => GET | URL:()
// Route::post('/users', [UserController::class, 'store']); //Metodo => POST | URL:()
// Route::put('/users/{id}', [UserController::class, 'update']); //Metodo => PUT | URL:()
// Route::delete('/users/{id}', [UserController::class, 'destroy']); //Metodo => DELETE | URL:()

// Rotas para gerenciamento de Usuario
Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');           // GET     /users
    Route::get('/users/{id}', 'show');       // GET     /users/{id}
    Route::post('/users', 'store');          // POST    /users
    Route::put('/users/{id}', 'update');     // PUT     /users/{id}
    Route::delete('/users/{id}', 'destroy'); // DELETE  /users/{id}
});