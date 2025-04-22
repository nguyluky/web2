<?php

use Illuminate\Support\Facades\Route;



// items

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/items', function () {
    return "Hello World";
});
Route::get('/items/{id}', function ($id) {
    return "Hello World $id";
});
