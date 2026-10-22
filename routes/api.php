<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MealController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/meals', [MealController::class, 'index']);
    Route::get('/meals/{id}', [MealController::class, 'show']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::get('/cart', [CartController::class, 'view']);
    Route::post('/order', [OrderController::class, 'store']);
});
