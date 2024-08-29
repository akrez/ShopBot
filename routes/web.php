<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogLogoController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PayvoiceController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductPropertyController;
use App\Http\Controllers\ProductTagController;
use App\Http\Controllers\SiteController;
use App\Http\Middleware\ActiveBlogMiddleware;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

if (App::isProduction()) {
    Route::domain('{host}')
        ->whereIn('host', resolve('Hosts')->getArrayKeys())
        ->get('/', [FrontController::class, 'domain']);
}

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get(AppServiceProvider::HOME, [BlogController::class, 'index'])->name('home');
    Route::patch('blogs/{id}/active', [BlogController::class, 'active'])->name('blogs.active');
    Route::resource('blogs', BlogController::class)->parameter('blogs', 'id')->except(['destroy']);
    Route::middleware(ActiveBlogMiddleware::class)->group(function () {

        Route::get('payvoices', [PayvoiceController::class, 'index'])->name('payvoices.index');

        Route::get('ports/{sheetName}', [PortController::class, 'index'])->name('ports.index');
        Route::get('ports/{sheetName}/export', [PortController::class, 'export'])->name('ports.export');
        Route::post('ports/{sheetName}', [PortController::class, 'index'])->name('ports.import');

        Route::post('bots/{id}/upload_attribute', [BotController::class, 'uploadAttribute'])->name('bots.upload_attribute');

        Route::resource('bots', BotController::class)->parameter('bots', 'id');
        Route::resource('products', ProductController::class)->parameter('products', 'id')->except('destroy');

        Route::get('products/{product_id}/product_tags', [ProductTagController::class, 'create'])->name('products.product_tags.create');
        Route::post('products/{product_id}/product_tags', [ProductTagController::class, 'store'])->name('products.product_tags.store');

        Route::get('products/{product_id}/product_properties', [ProductPropertyController::class, 'create'])->name('products.product_properties.create');
        Route::post('products/{product_id}/product_properties', [ProductPropertyController::class, 'store'])->name('products.product_properties.store');

        Route::resource('contacts', ContactController::class)->parameter('contacts', 'id');
        Route::resource('colors', ColorController::class)->parameter('colors', 'id');

        Route::resource('products/{product_id}/product_images', ProductImageController::class, ['as' => 'products'])->parameter('product_images', 'name')->except(['create', 'show']);
        Route::resource('blog_logos', BlogLogoController::class)->parameter('blog_logos', 'name');
    });
});

Route::get('/', [SiteController::class, 'index']);
Route::get('/gallery/{category}/{whmq}/{name}', [GalleryController::class, 'paint']);
Route::get('/fronts/{id}', [FrontController::class, 'index'])->name('fronts.index');
