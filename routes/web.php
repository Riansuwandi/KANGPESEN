<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// Home Routes
Route::get('/', [HomeController::class, 'index']);
Route::post('/add-to-order', [HomeController::class, 'addToOrder'])->middleware('auth');

// Menu Routes (Staff Only)
Route::middleware(['auth'])->group(function () {
    Route::get('/menu/create', [MenuController::class, 'create']);
    Route::post('/menu', [MenuController::class, 'store']);
    Route::delete('/menu/{menu}', [MenuController::class, 'destroy']);
});

// Order Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/confirm-order', [OrderController::class, 'confirmOrder']);
    Route::post('/process-confirm-order', [OrderController::class, 'processConfirmOrder']);
    Route::post('/finish-order', [OrderController::class, 'finishOrder']);
});

// Table Status Routes (Staff Only)
Route::middleware(['auth'])->group(function () {
    Route::get('/table-status', [HomeController::class, 'showTableStatus']);
    Route::post('/update-table-status/{meja}', [TableController::class, 'updateStatus']);
});