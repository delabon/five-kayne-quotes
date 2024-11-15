<?php

use App\Http\Controllers\Api\V1\QuoteController;
use Illuminate\Support\Facades\Route;

Route::get('/quotes', [QuoteController::class, 'random']);
