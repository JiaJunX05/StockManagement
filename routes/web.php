<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
// use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Storage\ZoneController;
use App\Http\Controllers\Admin\Storage\RackController;
use App\Http\Controllers\Admin\Storage\LocationController;
use App\Http\Controllers\Admin\Master\Category\CategoryController;
use App\Http\Controllers\Admin\Master\Category\SubCategoryController;
use App\Http\Controllers\Admin\Master\Category\MappingController;
use App\Http\Controllers\Admin\Master\BrandController;
use App\Http\Controllers\Admin\Master\ColorController;
use App\Http\Controllers\Admin\ProductController;

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
    Route::get('/dashboard', [AuthController::class, 'index'])->name('admin.dashboard');
    Route::get('/user/index', [AdminController::class, 'showUserList'])->name('user.index');

    Route::get('/admin/user-count', function () {
        return response()->json(['count' => \App\Models\User::count()]);
    })->name('admin.user.count');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    // Create Zone
    Route::get('/zone/index', [ZoneController::class, 'index'])->name('zone.index');
    Route::get('/zone/create', [ZoneController::class, 'create'])->name('zone.create');
    Route::post('/zone/store', [ZoneController::class, 'store'])->name('zone.store');
    Route::get('/zone/edit/{id}', [ZoneController::class, 'edit'])->name('zone.edit');
    Route::put('/zone/update/{id}', [ZoneController::class, 'update'])->name('zone.update');
    Route::delete('/zone/destroy/{id}', [ZoneController::class, 'destroy'])->name('zone.destroy');

    // Create Rack
    Route::get('/rack/index', [RackController::class, 'index'])->name('rack.index');
    Route::get('/rack/create', [RackController::class, 'create'])->name('rack.create');
    Route::post('/rack/store', [RackController::class, 'store'])->name('rack.store');
    Route::get('/rack/edit/{id}', [RackController::class, 'edit'])->name('rack.edit');
    Route::put('/rack/update/{id}', [RackController::class, 'update'])->name('rack.update');
    Route::delete('/rack/destroy/{id}', [RackController::class, 'destroy'])->name('rack.destroy');

    // Create Storage Location
    Route::get('/location/index', [LocationController::class, 'index'])->name('location.index');
    Route::get('/location/create', [LocationController::class, 'create'])->name('location.create');
    Route::post('/location/store', [LocationController::class, 'store'])->name('location.store');
    Route::get('/location/edit/{id}', [LocationController::class, 'edit'])->name('location.edit');
    Route::put('/location/update/{id}', [LocationController::class, 'update'])->name('location.update');
    Route::delete('/location/destroy/{id}', [LocationController::class, 'destroy'])->name('location.destroy');

    // Create Category
    Route::get('/category/index', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/category/update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    // Create SubCategory
    Route::get('/subcategory/index', [SubCategoryController::class, 'index'])->name('subcategory.index');
    Route::get('/subcategory/create', [SubCategoryController::class, 'create'])->name('subcategory.create');
    Route::post('/subcategory/store', [SubCategoryController::class, 'store'])->name('subcategory.store');
    Route::get('/subcategory/edit/{id}', [SubCategoryController::class, 'edit'])->name('subcategory.edit');
    Route::put('/subcategory/update/{id}', [SubCategoryController::class, 'update'])->name('subcategory.update');
    Route::delete('/subcategory/destroy/{id}', [SubCategoryController::class, 'destroy'])->name('subcategory.destroy');

    // Create Category Mapping
    Route::get('/mapping/index', [MappingController::class, 'index'])->name('mapping.index');
    Route::get('/mapping/create', [MappingController::class, 'create'])->name('mapping.create');
    Route::post('/mapping/store', [MappingController::class, 'store'])->name('mapping.store');
    Route::get('/mapping/edit/{id}', [MappingController::class, 'edit'])->name('mapping.edit');
    Route::put('/mapping/update/{id}', [MappingController::class, 'update'])->name('mapping.update');
    Route::delete('/mapping/destroy/{id}', [MappingController::class, 'destroy'])->name('mapping.destroy');

    // Create Brand
    Route::get('/brand/index', [BrandController::class, 'index'])->name('brand.index');
    Route::get('/brand/create', [BrandController::class, 'create'])->name('brand.create');
    Route::post('/brand/store', [BrandController::class, 'store'])->name('brand.store');
    Route::get('/brand/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
    Route::put('/brand/update/{id}', [BrandController::class, 'update'])->name('brand.update');
    Route::delete('/brand/destroy/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');

    // Create Color
    Route::get('/color/index', [ColorController::class, 'index'])->name('color.index');
    Route::get('/color/create', [ColorController::class, 'create'])->name('color.create');
    Route::post('/color/store', [ColorController::class, 'store'])->name('color.store');
    Route::get('/color/edit/{id}', [ColorController::class, 'edit'])->name('color.edit');
    Route::put('/color/update/{id}', [ColorController::class, 'update'])->name('color.update');
    Route::delete('/color/destroy/{id}', [ColorController::class, 'destroy'])->name('color.destroy');



    // Add Product
    Route::get('/product/list', [ProductController::class, 'index'])->name('product.list');
    Route::get('/product/create', [ProductController::class, 'showCreateForm'])->name('product.create');
    Route::post('/product/create', [ProductController::class, 'create'])->name('product.create.submit');
    Route::get('.product/view/{id}', [ProductController::class, 'view'])->name('product.view');
    Route::get('/product/stock/{id}', [ProductController::class, 'showStockForm'])->name('product.stock');
    Route::put('/product/stock/{id}', [ProductController::class, 'stockUpdate'])->name('product.stock.submit');
    Route::get('/product/update/{id}', [ProductController::class, 'showUpdateForm'])->name('product.update');
    Route::put('/product/update/{id}', [ProductController::class, 'update'])->name('product.update.submit');
    Route::delete('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
});

Route::prefix('staff')->middleware(['auth', 'role:staff'])->group(function() {
    Route::get('/dashboard', [AuthController::class, 'index'])->name('staff.dashboard');

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
