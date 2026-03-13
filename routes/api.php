<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

// Endpoint Autentikasi (Bebas akses untuk register & login)
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

// Endpoint Resource CRUD Produk (Wajib Login/Bawa Token JWT)
Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('products', ProductController::class);
});
