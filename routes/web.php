<?php

use App\Exports\PurchasesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DashboardController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('welcome');
});
Route::get('/error', function () {
    return view('error');
})->name('error.page');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware(['auth', 'CheckRole:admin,cashier'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
    Route::get('/purchase', [PurchaseController::class, 'data'])->name('purchase.index');
    Route::get('/purchases/detail/{id}', [PurchaseController::class, 'show'])->name('purchase.detail');
    Route::get('/purchase/{invoiceNumber}/download', [PurchaseController::class, 'downloadPdf'])->name('purchase.download');
    Route::get('/export-purchases', function (Request $request) {
        $range = $request->query('range', 'all'); // default ke 'all' jika tidak ada parameter
        $filename = "purchases_{$range}.xlsx";

        return Excel::download(new PurchasesExport($range), $filename);
    })->name('export.excel');
});

// ROUTES KHUSUS CASHIER
Route::middleware(['auth', 'CheckRole:cashier'])->group(function () {
    Route::get('/purchase/sale', [PurchaseController::class, 'sale'])->name('purchase.sale');
    Route::post('/purchase/sale/post', [PurchaseController::class, 'post'])->name('purchase.sale.post');
    Route::post('/purchase/sale/store', [PurchaseController::class, 'store'])->name('purchase.sale.store');
    Route::get('/purchase/member/create', [PurchaseController::class, 'createMember'])->name('purchase.member.create');
    Route::post('/purchase/member/store', [PurchaseController::class, 'purchaseMember'])->name('purchase.member.store');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
});

// ROUTES KHUSUS ADMIN
Route::middleware(['auth', 'CheckRole:admin'])->group(function () {
    // Products
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/data', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/updateStock/{id}', [ProductController::class, 'updateStock'])->name('products.updateStock');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});
