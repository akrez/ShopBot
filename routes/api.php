<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/blogs/{blog_id}', [ApiController::class, 'blog'])->name('api.blog');
