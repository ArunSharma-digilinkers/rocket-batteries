<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [CatalogController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [CatalogController::class, 'show'])->name('products.show');
Route::get('/categories/{category}', [CatalogController::class, 'category'])->name('categories.show');
Route::get('/series/{series}', [CatalogController::class, 'series'])->name('series.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
