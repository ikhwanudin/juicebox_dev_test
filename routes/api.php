<?php

use App\Http\Controllers\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;


// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']);
    Route::get('{post}', [PostController::class, 'show']);
});

Route::get('weather', [WeatherController::class, 'show']);

// Authenticated Routes
Route::middleware(['auth:sanctum'])->group(function (){
    Route::prefix('posts')->group(function (){
        Route::patch('{post}', [PostController::class, 'update']);
        Route::post('/', [PostController::class, 'store']);
        Route::delete('/', [PostController::class, 'destroy']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users/{id}', [AuthController::class, 'show']);
});
