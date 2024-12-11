<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\ProductController;

Route::get('/', [ProductController::class, 'getProducts'])->name('products.index');

Route::prefix('products')->group(function () {
    Route::get('/categories', [ProductController::class, 'categories'])->name('products.categories');
    Route::get('/filter', [ProductController::class, 'filter'])->name('products.filter');
    Route::get('/search', [ProductController::class, 'search'])->name('products.search');
});
