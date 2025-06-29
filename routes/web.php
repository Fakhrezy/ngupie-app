<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
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
use App\Http\Controllers\ManagerController;

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk login manual test
Route::get('/login/staff', function() {
    Session::put('authenticated', true);
    Session::put('user', [
        'email' => 'staff@coffeshop.com',
        'name' => 'Staff Coffee Shop',
        'role' => 'Staff'
    ]);

    return redirect()->route('staff.index')->with('success', 'Login berhasil sebagai staff');
})->name('login.staff');

// Route untuk login manual manager
Route::get('/login/manager', function() {
    Session::put('authenticated', true);
    Session::put('user', [
        'email' => 'manager@coffeeshop.com',
        'name' => 'Manager Coffee Shop',
        'role' => 'Manager'
    ]);

    return redirect()->route('manager.index')->with('success', 'Login berhasil sebagai manager');
})->name('login.manager');

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
    Route::post('/staff/checkin', [StaffController::class, 'checkIn'])->name('staff.checkin');
    Route::post('/staff/checkout', [StaffController::class, 'checkOut'])->name('staff.checkout');
    Route::post('/staff/update-status', [StaffController::class, 'updateStatus'])->name('staff.update-status');
    Route::get('/staff/report', [StaffController::class, 'report'])->name('staff.report');
    Route::get('/staff/attendance-updates', [StaffController::class, 'getAttendanceUpdates'])->name('staff.attendance-updates');
    Route::post('/staff/request-leave', [StaffController::class, 'requestLeave'])->name('staff.request-leave');
    Route::post('/staff/break', [StaffController::class, 'break'])->name('staff.break');
});

// Manager routes (require authentication and manager role)
Route::middleware(['manager.access'])->group(function () {
    Route::get('/manager', [ManagerController::class, 'index'])->name('manager.index');
    Route::post('/manager/update-status', [ManagerController::class, 'updateStatus'])->name('manager.update-status');
    Route::post('/manager/bulk-update', [ManagerController::class, 'bulkUpdate'])->name('manager.bulk-update');
    Route::post('/manager/export', [ManagerController::class, 'export'])->name('manager.export');
    Route::post('/manager/approve-leave', [ManagerController::class, 'approveLeave'])->name('manager.approve-leave');
    Route::get('/manager/attendance-updates', [ManagerController::class, 'getAttendanceUpdates'])->name('manager.attendance-updates');
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

// Test route
Route::get('/staff/test', [StaffController::class, 'test'])->name('staff.test');
