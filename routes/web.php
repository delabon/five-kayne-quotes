<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->middleware('auth');

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
