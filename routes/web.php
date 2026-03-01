<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SaleController as AdminSaleController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('kasir.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class)->except(['show', 'create']);
        Route::resource('suppliers', SupplierController::class)->except(['show', 'create']);
        Route::resource('products', ProductController::class)->except(['show', 'create']);
        Route::resource('users', UserController::class)->except(['show', 'create']);
        Route::get('sales', [AdminSaleController::class, 'index'])->name('sales.index');
        Route::get('sales/export/excel', [AdminSaleController::class, 'exportExcel'])->name('sales.export.excel');
        Route::get('sales/export/pdf', [AdminSaleController::class, 'exportPdf'])->name('sales.export.pdf');
        Route::get('sales/{sale}', [AdminSaleController::class, 'show'])->name('sales.show');
    });

    Route::middleware('role:kasir')->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/add', [PosController::class, 'addToCart'])->name('pos.add');
        Route::post('/pos/update', [PosController::class, 'updateCart'])->name('pos.update');
        Route::post('/pos/clear', [PosController::class, 'clearCart'])->name('pos.clear');
        Route::post('/pos/stand', [PosController::class, 'setStandLocation'])->name('pos.stand');
        Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
        Route::get('/sales', [PosController::class, 'sales'])->name('sales.index');
        Route::get('/sales/{sale}', [PosController::class, 'showSale'])->name('sales.show');
        Route::post('/sales/{sale}/send-whatsapp', [PosController::class, 'sendWhatsApp'])->name('sales.send-whatsapp');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
