<?php

use App\Http\Controllers\Api\OrderCommandController;
use App\Http\Controllers\Api\OrderQueryController;
use Illuminate\Support\Facades\Route;

// Command side (write)
Route::post('/orders', [OrderCommandController::class, 'store']);

// Query side (read)
Route::get('/orders', [OrderQueryController::class, 'index']);
Route::get('/orders/{id}', [OrderQueryController::class, 'show']);
