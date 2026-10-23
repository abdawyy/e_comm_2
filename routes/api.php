<?php

use App\Http\Controllers\Api\StoreApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/categories', [StoreApiController::class, 'categories']);
    Route::get('/products', [StoreApiController::class, 'products']);
    Route::get('/products/{product}', [StoreApiController::class, 'product']);
    Route::post('/cart/quote', [StoreApiController::class, 'quote']);
    Route::post('/orders', [StoreApiController::class, 'storeOrder']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [StoreApiController::class, 'user']);
        Route::get('/orders', [StoreApiController::class, 'orders']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [StoreApiController::class, 'user']);
    Route::get('/orders', [StoreApiController::class, 'orders']);
});
