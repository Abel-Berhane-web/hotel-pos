<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomRentalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\LanguageController;

// Redirect root to dashboard
Route::get('/', fn() => redirect('/dashboard'));

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Language switch
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Authenticated routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Orders (cashier, manager, admin)
    Route::middleware('role:admin,manager,cashier')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    });
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy')->middleware('role:admin');

    // Products (manager, admin)
    Route::middleware('role:admin,manager')->group(function () {
        Route::resource('products', ProductController::class)->except(['show']);
        Route::post('/products/{product}/stock', [StockController::class, 'adjust'])->name('products.stock');
    });

    // Categories (admin)
    Route::resource('categories', CategoryController::class)->except(['show'])->middleware('role:admin');

    // Rooms (receptionist, manager, admin)
    Route::middleware('role:admin,manager,receptionist')->group(function () {
        Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
        Route::post('/rooms/{room}/rent', [RoomRentalController::class, 'store'])->name('rooms.rent');
        Route::post('/rooms/{room}/checkout', [RoomRentalController::class, 'checkout'])->name('rooms.checkout');
        Route::post('/rentals/{rental}/confirm-payment', [RoomRentalController::class, 'confirmPayment'])->name('rooms.confirm_payment');
        Route::get('/rentals', [RoomRentalController::class, 'index'])->name('rentals.index');
        Route::get('/rentals/{rental}', [RoomRentalController::class, 'show'])->name('rentals.show');
        Route::get('/reservations', [RoomRentalController::class, 'reservations'])->name('reservations.index');
        Route::post('/reservations/{rental}/check-in', [RoomRentalController::class, 'checkInReservation'])->name('reservations.check_in');
        Route::post('/reservations/{rental}/cancel', [RoomRentalController::class, 'cancelReservation'])->name('reservations.cancel');
    });
    Route::middleware('role:admin,receptionist')->group(function () {
        Route::get('/rooms/manage', [RoomController::class, 'manage'])->name('rooms.manage');
        Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
        Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
        Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
    });

    // Reports (admin, manager)
    Route::middleware('role:admin,manager')->prefix('reports')->group(function () {
        Route::get('/daily', [ReportController::class, 'daily'])->name('reports.daily');
        Route::get('/period', [ReportController::class, 'period'])->name('reports.period');
        Route::get('/product-sales', [ReportController::class, 'productSales'])->name('reports.product-sales');
        Route::get('/stock', [ReportController::class, 'stock'])->name('reports.stock');
    });

    // Users (admin)
    Route::resource('users', UserController::class)->except(['show'])->middleware('role:admin');

    // Settings (admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // Audit Log (admin, manager)
    Route::get('/audit', [AuditLogController::class, 'index'])->name('audit.index')->middleware('role:admin,manager');

    // Notifications
    Route::get('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
});
