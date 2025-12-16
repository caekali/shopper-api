<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/home', [HomeController::class, 'index'])->middleware('auth:sanctum');

Route::get('/profile', [UserController::class, 'getUser'])->middleware('auth:sanctum');

Route::prefix('/auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);

    Route::post('/signin', [AuthController::class, 'signin']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'delete']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'delete']);
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories/{id}/products', [ProductController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

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
