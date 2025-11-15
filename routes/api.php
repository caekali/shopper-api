<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('/auth')->group(function(){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
});


Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
Route::get('/products/{id}', [ProductController::class, 'show']);


Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add', [CartController::class, 'store']);
Route::delete('/cart/remove', [CartController::class, 'destroy']);
Route::get('/cart/clear', [CartController::class, 'clear']);
