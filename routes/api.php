<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\Products;
use App\Http\Controllers\user\CategoryController;

Route::prefix('admin')->group(function () {
    Route::controller(Products::class)->group(function () {
        Route::post('/products', 'create');
        Route::get('/products', 'getAll');
        Route::get('/products/{id}', 'getById');
    });
});

Route::controller(CategoryController::class)->group(function () {
    // product
    Route::get('/products/{id}', 'getById');
    Route::get('/categories/{id}/products', 'getByCategory');
    Route::get('/products/search?query={query}&page={page}&limit={limit}&sort={sort}', 'searchProduct');


    // category
    Route::get('/categories', 'getAll');

    // review
    Route::get('/products/{id}/reviews', 'getReviewsByProductId');
    Route::post('/products/{id}/reviews', 'addReviewById');

    // cart
    Route::post('/cart', 'addCart');
    Route::get('/cart', 'getAllCart');
    Route::put('/cart/{variant_id}', 'updateCart');
    Route::delete('/cart/{variant_id}', 'deleteCart');
    
    // order
    Route::post('/orders', 'createOrders');

    // account
    Route::post('auth/register', 'register');
    Route::post('auth/login', 'login');
});

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
        'status' => 200
    ]);
});