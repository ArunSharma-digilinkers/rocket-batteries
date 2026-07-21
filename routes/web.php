<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WarrantyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/products', [CatalogController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [CatalogController::class, 'show'])->name('products.show');
Route::get('/categories/{category}', [CatalogController::class, 'category'])->name('categories.show');
Route::get('/series/{series}', [CatalogController::class, 'series'])->name('series.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');

Route::get('/warranty', [WarrantyController::class, 'index'])->name('warranty');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
