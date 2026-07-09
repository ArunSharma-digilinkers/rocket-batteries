<?php

use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SeriesController;
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

        Route::post('series/{series}/attributes', [SeriesController::class, 'syncAttributes'])->name('series.attributes.sync');
    });
});
