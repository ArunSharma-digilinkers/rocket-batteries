<?php

use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SeriesController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\WarrantyController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('series', SeriesController::class)->except('show');
        Route::resource('attributes', AttributeController::class)->except('show');
        Route::resource('applications', ApplicationController::class)->except('show');
        Route::resource('products', ProductController::class)->except(['show', 'store', 'update']);
        Route::resource('blog', BlogController::class)->except('show');
        Route::post('blog-images', [BlogController::class, 'uploadImage'])->name('blog.images.upload');
        Route::post('blog-categories', [BlogController::class, 'storeCategory'])->name('blog.categories.store');
        Route::delete('blog-categories/{category}', [BlogController::class, 'destroyCategory'])->name('blog.categories.destroy');

        Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');
        Route::post('gallery/images', [GalleryController::class, 'storeImages'])->name('gallery.images.store');
        Route::put('gallery/images/{image}', [GalleryController::class, 'updateImage'])->name('gallery.images.update');
        Route::delete('gallery/images/{image}', [GalleryController::class, 'destroyImage'])->name('gallery.images.destroy');

        Route::post('series/{series}/attributes', [SeriesController::class, 'syncAttributes'])->name('series.attributes.sync');

        Route::resource('enquiries', EnquiryController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('warranties', WarrantyController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('subscribers', SubscriberController::class)->only(['index', 'destroy']);
    });
});
