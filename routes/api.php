<?php

use App\Http\Controllers\API\CategoriesController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('products')->name('products.')->group(function() {

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/{ìd}', [ProductController::class, 'show']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/{ìd}', [ProductController::class, 'update']); 
    Route::delete('/product/{ìd}', [ProductController::class, 'destroy']); 
});

Route::prefix('categories')->name('categories.')->group(function() {

    Route::get('/categories', [CategoriesController::class, 'index']);
    Route::get('/{ìd}', [CategoriesController::class, 'show']);
    Route::post('/categories', [CategoriesController::class, 'store']);
    Route::put('/{ìd}', [CategoriesController::class, 'update']); 
    Route::delete('/category/{ìd}', [CategoriesController::class, 'destroy']); 
});