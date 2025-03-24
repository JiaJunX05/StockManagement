<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
// use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\Admin\RackController;
use App\Http\Controllers\Admin\StorackController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProductController;

// Guest routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Password Reset
// Route::get('/reset-password', [ResetPasswordController::class, 'ShowRequestForm'])->name('password.request');
// Route::post('/password/email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Route::post('/password/reset', [ResetPasswordController::class, 'resetPassword'])->name('password.update');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function() {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'showUserList'])->name('user.list');

    Route::get('/admin/user-count', function () {
        return response()->json(['count' => \App\Models\User::count()]);
    })->name('admin.user.count');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    // Add Category
    Route::get('/category/list', [CategoryController::class, 'index'])->name('category.list');
    Route::get('/category/create', [CategoryController::class, 'showCreateForm'])->name('category.create');
    Route::post('/category/create', [CategoryController::class, 'create'])->name('category.create.submit');
    Route::get('/category/update/{id}', [CategoryController::class, 'showUpdateForm'])->name('category.update');
    Route::put('/category/update/{id}', [CategoryController::class, 'update'])->name('category.update.submit');
    Route::delete('/category/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    // Add Zone
    Route::get('/zone/list', [ZoneController::class, 'index'])->name('zone.list');
    Route::get('/zone/create', [ZoneController::class, 'showCreateForm'])->name('zone.create');
    Route::post('/zone/create', [ZoneController::class, 'create'])->name('zone.create.submit');
    Route::get('/zone/update/{id}', [ZoneController::class, 'showUpdateForm'])->name('zone.update');
    Route::put('/zone/update/{id}', [ZoneController::class, 'update'])->name('zone.update.submit');
    Route::delete('/zone/delete/{id}', [ZoneController::class, 'destroy'])->name('zone.destroy');

    // Add Rack
    Route::get('/rack/list', [RackController::class, 'index'])->name('rack.list');
    Route::get('/rack/create', [RackController::class, 'showCreateForm'])->name('rack.create');
    Route::post('/rack/create', [RackController::class, 'create'])->name('rack.create.submit');
    Route::get('/rack/update/{id}', [RackController::class, 'showUpdateForm'])->name('rack.update');
    Route::put('/rack/update/{id}', [RackController::class, 'update'])->name('rack.update.submit');
    Route::delete('/rack/delete/{id}', [RackController::class, 'destroy'])->name('rack.destroy');

    // Add Storack
    Route::get('/storack/list', [StorackController::class, 'index'])->name('storack.list');
    Route::get('/storack/create', [StorackController::class, 'showCreateForm'])->name('storack.create');
    Route::post('/storack/create', [StorackController::class, 'create'])->name('storack.create.submit');
    Route::get('/storack/update/{id}', [StorackController::class, 'showUpdateForm'])->name('storack.update');
    Route::put('/storack/update/{id}', [StorackController::class, 'update'])->name('storack.update.submit');
    Route::delete('/storack/delete/{id}', [StorackController::class, 'destroy'])->name('storack.destroy');

    // Add Product
    Route::get('/product/list', [AdminProductController::class, 'index'])->name('product.list');
    Route::get('/product/create', [AdminProductController::class, 'showCreateForm'])->name('product.create');
    Route::post('/product/create', [AdminProductController::class, 'create'])->name('product.create.submit');
    Route::get('.product/view/{id}', [AdminProductController::class, 'view'])->name('product.view');
    Route::get('/product/stock/{id}', [AdminProductController::class, 'showStockForm'])->name('product.stock');
    Route::put('/product/stock/{id}', [AdminProductController::class, 'stockUpdate'])->name('product.stock.submit');
    Route::get('/product/update/{id}', [AdminProductController::class, 'showUpdateForm'])->name('product.update');
    Route::put('/product/update/{id}', [AdminProductController::class, 'update'])->name('product.update.submit');
    Route::delete('/product/delete/{id}', [AdminProductController::class, 'destroy'])->name('product.destroy');
});

Route::prefix('staff')->middleware(['auth', 'role:staff'])->group(function() {
    Route::get('/dashboard', [staffController::class, 'index'])->name('staff.dashboard');

    // Add Product
    Route::get('/product/list', [ProductController::class, 'index'])->name('list');
    Route::get('/product/create', [ProductController::class, 'showCreateForm'])->name('create');
    Route::post('/product/create', [ProductController::class, 'create'])->name('create.submit');
    Route::get('.product/view/{id}', [ProductController::class, 'view'])->name('view');
    Route::get('/product/stock/{id}', [ProductController::class, 'showStockForm'])->name('stock');
    Route::put('/product/stock/{id}', [ProductController::class, 'stockUpdate'])->name('stock.submit');
    Route::get('/product/update/{id}', [ProductController::class, 'showUpdateForm'])->name('update');
    Route::put('/product/update/{id}', [ProductController::class, 'update'])->name('update.submit');
    // Route::delete('/product/delete/{id}', [ProductController::class, 'destroy'])->name('destroy');
});
