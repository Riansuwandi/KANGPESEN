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
Route::post('/remove-from-order', [HomeController::class, 'removeFromOrder'])->middleware('auth');
Route::post('/clear-order', [HomeController::class, 'clearOrder'])->middleware('auth');

// Menu Routes (Staff Only)
Route::middleware(['auth'])->group(function () {
    Route::get('/menu/create', [MenuController::class, 'create'])->name('menu.create');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::get('/menu/{menu}/edit', [MenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');
});

// Order Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/confirm-order', [OrderController::class, 'confirmOrder'])->name('confirm.order');
    Route::post('/process-confirm-order', [OrderController::class, 'processConfirmOrder'])->name('process.confirm.order');
    Route::get('/order-status', [OrderController::class, 'orderStatus'])->name('order.status');
    Route::post('/update-item-status', [OrderController::class, 'updateItemStatus'])->name('update.item.status');
    Route::post('/complete-order', [OrderController::class, 'completeOrder'])->name('complete.order');
    Route::post('/finish-order', [OrderController::class, 'finishOrder']); // Backward compatibility
});

// Table Status Routes (Staff Only)
Route::middleware(['auth'])->group(function () {
    Route::get('/table-status', [HomeController::class, 'showTableStatus'])->name('table.status');
    Route::post('/update-table-status/{meja}', [TableController::class, 'updateStatus'])->name('update.table.status');
});
