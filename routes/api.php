<?php

use App\Http\Controllers\Api\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/blog/{id}', [BlogController::class, 'index']);
