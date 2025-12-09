<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->prefix('cart')->group(function () {
    Route::get('', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'store']);
    Route::get('/status/{productId}', [CartController::class, 'getProductStatus']);
    Route::delete('/remove/{itemId}', [CartController::class, 'destroy']);
    Route::delete('/clear', [CartController::class, 'clear']);
});

Route::post('/checkout', [OrderController::class, 'checkout'])->middleware('auth:sanctum');
Route::get('/payment/callback', [OrderController::class, 'paymentCallback'])->name('payment.callback');
Route::get('/payment/return-url', [OrderController::class, 'paymentReturnUrl'])->name('payment.return.url');
