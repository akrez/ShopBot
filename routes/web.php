<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SiteController;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [SiteController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get(AppServiceProvider::HOME, [HomeController::class, 'index'])->name('home');
});
