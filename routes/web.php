<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [CatalogController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [CatalogController::class, 'show'])->name('products.show');
Route::get('/categories/{category}', [CatalogController::class, 'category'])->name('categories.show');
Route::get('/series/{series}', [CatalogController::class, 'series'])->name('series.show');
