<?php

use App\Http\Controllers\user\AccountController;
use App\Http\Controllers\user\AddressController;
use App\Http\Controllers\user\CartController;
use App\Http\Controllers\user\OrderController;
use App\Http\Controllers\user\ProductController;
use App\Http\Controllers\user\ProductReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\Products;
use App\Http\Controllers\user\CategoryController;
use App\Http\Controllers\user\ProfileController;

// products
Route::prefix('admin')->group(function () {
    Route::controller(Products::class)->group(function () {
        Route::post('/products', 'create');
        Route::get('/products', 'getAll');
        Route::get('/products/{id}', 'getById');
    });
});


// product
Route::controller(ProductController::class)->group(function () {
    Route::get('/products/search', 'searchProduct');
    Route::get('/products/new', 'getNewProduct');
    Route::get('/products/{id}', 'getById');
    Route::get('/categories/{id}/products', 'getByCategory');
    // Route::get('/products', 'getAll');
});

// review
Route::controller(ProductReviewController::class)->group(function () {
    Route::get('/products/{id}/reviews', 'getReviewsByProductId');
    Route::post('/products/{id}/reviews', 'addReviewById');
});

// cart
Route::middleware(['auth:api'])->group(function () {

    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'getAllCart');
        Route::post('/cart', 'addCart');
        Route::put('/cart/{variant_id}', 'updateCart');
        Route::delete('/cart/{variant_id}', 'deleteCart');
    });

    Route::controller(OrderController::class)->group(function () {
        // order
        Route::post('/orders', 'createOrders');
        Route::get('/user/orders', 'getUserOrders');
        Route::get('/orders/{id}', 'getOrderDetail');
        Route::put('/orders/{id}/cancel', 'cancelOrder');
    });

    Route::controller(AddressController::class)->group(function () {
        // address
        Route::post('/users/addresses', 'addAddress');
        Route::get('/users/addresses', 'getUserAddress');
        Route::put('/users/addresses/{id}', 'updateAddress');
        Route::delete('/users/addresses/{id}', 'deleteAddress');
    });

    Route::get('/users/profile', [AccountController::class, 'getById']);
    Route::put('/users/profile', [AccountController::class, 'update']);
});


// account
Route::controller(AccountController::class)->group(function () {
    Route::post('auth/register', 'register');
    Route::post('auth/login', 'login')->name('login');
    // Route::get('/users/profile', 'getById');
    // Route::put('/users/profile', 'update');
    Route::put('/users/change-password', 'changePassword');
    // khong biet chen do dung khong nhung ma lam dai ____
    Route::get('/users/forgot-password', 'forgetPassword');
    // ___
    Route::put('/users/reset-password', 'resetPassword');
});

// category
Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'getAll');
});

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
        'status' => 200
    ]);
});
