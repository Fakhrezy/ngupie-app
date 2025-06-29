<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\BaristaController;
use App\Http\Controllers\StaffController;

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Barista routes (require authentication and barista role)
Route::middleware(['barista.access'])->group(function () {
    Route::get('/barista', [BaristaController::class, 'index'])->name('barista.index');
    Route::post('/barista/update-status', [BaristaController::class, 'updateStatus'])->name('barista.update-status');
    Route::get('/barista/recipe/{itemName}', [BaristaController::class, 'getRecipe'])->name('barista.recipe');
});

// Cashier routes (require authentication and cashier role)
Route::middleware(['cashier.access'])->group(function () {
    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');
    Route::post('/cashier/add-to-cart', [CashierController::class, 'addToCart'])->name('cashier.add-to-cart');
    Route::post('/cashier/payment', [CashierController::class, 'processPayment'])->name('cashier.payment');
});

// Staff routes (require authentication and staff role)
Route::middleware(['staff.access'])->group(function () {
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff/check-in', [StaffController::class, 'checkIn'])->name('staff.check-in');
    Route::post('/staff/check-out', [StaffController::class, 'checkOut'])->name('staff.check-out');
});

// Protected routes (require authentication and admin access)
Route::middleware(['admin.access'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes untuk manajemen data
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});
