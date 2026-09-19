<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController as ApiProductController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Specific routes BEFORE the wildcard resource routes
Route::get('/products/low-stock', [ApiProductController::class, 'lowStock']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::patch('/products/{product}/stock', [ApiProductController::class, 'adjustStock']);
});

// Public reads
Route::apiResource('products', ApiProductController::class)
    ->only(['index', 'show'])
    ->names('api.products');

// Admin-only writes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('products', ApiProductController::class)
        ->except(['index', 'show'])
        ->names('api.products');
});