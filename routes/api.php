<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\Products;


Route::prefix('admin')->group(function () {
    Route::controller(Products::class)->group(function () {
        Route::get('/products', 'getAll');
        Route::post('/products', 'create');
        Route::get('/products/{id}', 'getById');
    });
});


Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
        'status' => 200
    ]);
});