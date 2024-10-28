<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;
<<<<<<< HEAD
use App\Http\Controllers\CategoryController;

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
=======

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
// Route::post('/register', [AuthController::class, 'register']);
>>>>>>> 6decea440c456a0f635695f0c2f9c5e9d191b254

// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Transactions
    Route::get('/transactions', [TransaksiController::class, 'index']);
    Route::get('/transactions/search', [TransaksiController::class, 'search']);
    Route::put('/transactions/{transaction}/update-status', [TransaksiController::class, 'updateStatus']);

    // POS System
    Route::get('/pos', [POSController::class, 'index']);
    Route::get('/pos/search', [POSController::class, 'search']);
    Route::get('/pos/reset', [POSController::class, 'reset']);
    Route::get('/cart', [POSController::class, 'cart']);
    Route::post('/checkout', [POSController::class, 'checkout']);

    // Products
    Route::apiResource('products', MenuController::class);

<<<<<<< HEAD
    //Category
    Route::get('/category', [CategoryController::class, 'show']);
    Route::post('/category/create', [CategoryController::class, 'store']);

=======
>>>>>>> 6decea440c456a0f635695f0c2f9c5e9d191b254
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
