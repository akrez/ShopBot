<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductPropertyController;
use App\Http\Controllers\ProductTagController;
use App\Http\Controllers\SiteController;
use App\Http\Middleware\ActiveBlogMiddleware;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [SiteController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get(AppServiceProvider::HOME, [HomeController::class, 'index'])->name('home');
    Route::patch('blogs/{id}/active', [BlogController::class, 'active'])->name('blogs.active');
    Route::resource('blogs', BlogController::class)->parameter('blogs', 'id');
    Route::middleware(ActiveBlogMiddleware::class)->group(function () {
        Route::get('port', [PortController::class, 'index'])->name('port.index');
        Route::post('port/import', [PortController::class, 'import'])->name('port.import');
        Route::get('port/export', [PortController::class, 'export'])->name('port.export');
        Route::resource('products', ProductController::class)->parameter('products', 'id');
        Route::get('products/{product_id}/product_tags', [ProductTagController::class, 'create'])->name('products.product_tags.create');
        Route::post('products/{product_id}/product_tags', [ProductTagController::class, 'store'])->name('products.product_tags.store');
        Route::get('products/{product_id}/product_properties', [ProductPropertyController::class, 'create'])->name('products.product_properties.create');
        Route::post('products/{product_id}/product_properties', [ProductPropertyController::class, 'store'])->name('products.product_properties.store');
        Route::resource('products/{product_id}/product_images', ProductImageController::class, ['as' => 'products'])->parameter('product_images', 'name');
        Route::resource('contacts', ContactController::class)->parameter('contacts', 'id');
    });
});
