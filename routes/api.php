<?php

use App\Http\Controllers\user\AccountController;
use App\Http\Controllers\user\AddressController;
use App\Http\Controllers\user\CartController;
use App\Http\Controllers\user\OrderController;
use App\Http\Controllers\user\ProductController;
use App\Http\Controllers\user\ProductReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\Products;
use App\Http\Controllers\admin\ProductVariants;
use App\Http\Controllers\admin\Imports;
use App\Http\Controllers\admin\Profiles;
use App\Http\Controllers\admin\ImportDetails;
use App\Http\Controllers\admin\Categorys;
use App\Http\Controllers\admin\Orders;
use App\Http\Controllers\admin\Suppliers;
use App\Http\Controllers\admin\Warrantys;
use App\Http\Controllers\admin\Statistical;
use App\Http\Controllers\admin\Accounts;
use App\Http\Controllers\admin\Rules;
use App\Http\Controllers\user\CategoryController;
use App\Http\Controllers\user\ProfileController;
use OpenApi\Annotations as OA;

// products
Route::prefix('admin')->group(function () {
    Route::controller(Products::class)->group(function () {
        Route::post('/products', 'create');
        Route::get('/products', 'getAll');
        Route::get('/products/{id}', 'getById');
        Route::put('/products/{id}', 'update');
        Route::delete('/products/{id}', 'delete');
        Route::post('/products/{id}', 'addStatus');
        Route::put('/products/{id}', 'updateStatus');
        Route::delete('/products/{id}', 'updateStatus');
    });
});

// categories
Route::prefix('admin')->group(function () {
    Route::controller(Categorys::class)->group(function () {
        Route::post('/categories', 'create');
        Route::get('/categories', 'getAll');
        Route::get('/categories/{id}', 'getById');
        Route::put('/categories/{id}', 'update');
        Route::delete('/categories/{id}', 'delete');
    });
});

// orders
Route::prefix('admin')->group(function () {
    Route::controller(Orders::class)->group(function () {
        Route::get('/orders', 'getAll');
        Route::get('/orders/{id}', 'getById');
        Route::put('/orders/{id}', 'updateStatus');
        Route::delete('/orders/{id}', 'cancelOrder');
        Route::get('/orders/{id}/details', 'getOrderDetails');
    });
});

// suppliers
Route::prefix('admin')->group(function () {
    Route::controller(Suppliers::class)->group(function () {
        Route::post('/suppliers', 'create');
        Route::get('/suppliers', 'getAll');
        Route::get('/suppliers/{id}', 'getById');
        Route::put('/suppliers/{id}', 'update');
        Route::delete('/suppliers/{id}', 'delete');
    });
});

// import
Route::prefix('admin')->group(function () {
    Route::controller(Imports::class)->group(function () {
        Route::post('/imports', 'create');
        Route::get('/imports', 'getAll');
        Route::get('/imports/{id}', 'getById');
        Route::put('/imports/{id}', 'updateStatus');
        Route::delete('/imports/{id}', 'cancelImport');
    });
});

//Warranty
Route::prefix('admin')->group(function () {
    Route::controller(Warrantys::class)->group(function () {
        Route::post('/warrantys', 'create');
        Route::get('/warrantys', 'getAll');
        Route::get('/warrantys/{id}', 'getById');
        Route::put('/warrantys/{id}', 'update');
        Route::delete('/warrantys/{id}', 'delete');
    });
});

// statistical 
Route::prefix('admin')->controller(Statistical::class)->group(function () {
    Route::get('revenue-cost', 'revenueCost');
    Route::get('inventory', 'inventory');
    Route::get('dashboard', 'dashboard');
    Route::get('revenue-by-products', 'revenueByProducts');
    Route::get('revenue-by-categories', 'revenueByCategories');
});

Route::prefix('admin')->group(function () {
    // account
    Route::controller(Accounts::class)->group(function () {
        Route::post('/accounts', 'create');
        Route::get('/accounts', 'getAll');
        Route::get('/accounts/{id}', 'getById');
        Route::put('/accounts/{id}', 'update');
        Route::delete('/accounts/{id}', 'delete');
    });

    // rule
    Route::controller(Rules::class)->group(function () {
        Route::post('/rules', 'create');
        Route::get('/rules', 'getAll');
        Route::get('/rules/{id}', 'getById');
        Route::put('/rules/{id}', 'update');
        Route::delete('/rules/{id}', 'delete');
    });

    // product variant
    Route::controller(ProductVariants::class)->group(function () {
        Route::get('/product-variants', 'getAll');
    });

    //prolife
    Route::controller(Profiles::class)->group(function () {
        Route::post('/users', 'create');
        Route::get('/users', 'getAll');
        Route::get('/users/{id}', 'getById');
        Route::put('/users/{id}', 'update');
        Route::delete('/users/{id}', 'delete');
    });

    // import detail
    Route::controller(ImportDetails::class)->group(function () {
        Route::post('/import-details', 'create');
        Route::get('/import-details', 'getAll');
        Route::get('/import-details/{id}', 'getById');
        Route::put('/import-details/{id}', 'update');
        Route::delete('/import-details/{id}', 'delete');
    });
});

// product
Route::controller(ProductController::class)->group(function () {
    Route::get('/products/search', 'searchProduct');
    Route::get('/products/new', 'getNewProduct');
    Route::get('/products/{id}', 'getById')->where('id', '[0-9]+');
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

    Route::get('/users/profile', [ProfileController::class, 'getProfile']);
    Route::put('/users/profile', [ProfileController::class, 'updateProfile']);
    Route::post('/users/profile/avatar', [ProfileController::class, 'uploadAvatar']);
});


// account
Route::controller(AccountController::class)->group(function () {
    Route::post('auth/register', 'register');
    Route::post('auth/login', 'login')->name('login');
    Route::put('/users/change-password', 'changePassword');
    // khong biet chen do dung khong nhung ma lam dai ____
    Route::get('/users/forgot-password', 'forgetPassword');
    // ___
    Route::put('/users/reset-password', 'resetPassword');
});

// category
Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'getAll');
    Route::get('/categories/{id}/filter', 'getFilter');
    Route::get('/categories/{id}', 'getById');
    // Route::get('/categories/{id}/products', 'getProductsByCategory');
});

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
        'status' => 200
    ]);
});
