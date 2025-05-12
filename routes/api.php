<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index']); //Metodo => GET | URL:()
Route::get('/users/{user}', [UserController::class, 'show']); //Metodo => GET | URL:()
