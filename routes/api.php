<?php

use Illuminate\Http\Request;

Route::resource('products', 'ProductController');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
