<?php

use App\Http\Controllers\HomeController;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get(AppServiceProvider::HOME, [HomeController::class, 'index'])->name('home');
