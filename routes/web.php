<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SellerRegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TrackController;
Route::get('/', function () {
    return view('login');
});

# Halaman Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

# Halaman Register Customer
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

# Halaman Reset Password
Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.request');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');

// Proteksi route customer (termasuk akses register seller)
Route::middleware(['auth:customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');

    // Register seller hanya bisa diakses oleh customer yang login
    Route::get('/seller/register', [SellerRegisterController::class, 'showRegistrationForm'])->name('seller.register.form');
    Route::post('/seller/register', [SellerRegisterController::class, 'register'])->name('seller.register.submit');
});

// Proteksi route admin dengan prefix 'admin'
Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Verifikasi seller oleh admin
    Route::get('/sellers/verify', [AdminController::class, 'verifySellers'])->name('admin.sellers.verify');
    Route::post('/seller/{id}/approve', [AdminController::class, 'approveSeller'])->name('admin.seller.approve');
    Route::post('/seller/{id}/reject', [AdminController::class, 'rejectSeller'])->name('admin.seller.reject');

    // Verifikasi produk
    Route::get('/products/verify', [AdminController::class, 'verifyProducts'])->name('admin.products.verify');
    Route::post('/products/approve/{id}', [AdminController::class, 'approveProduct'])->name('admin.products.approve');
    Route::post('/products/reject/{id}', [AdminController::class, 'rejectProduct'])->name('admin.products.reject');

    // Detail seller (pastikan route ini tetap ada)
    Route::get('/seller/{id}', [AdminController::class, 'show'])->name('admin.show');
});

# Proteksi route seller dan semua resource-nya
Route::middleware(['auth:seller'])->group(function () {
    Route::get('/seller/dashboard', [SellerController::class, 'dashboard'])->name('seller.dashboard');

    // Resource product
    Route::resource('products', ProductController::class);
});

// Route untuk tampilkan form edit profile
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
// Route untuk proses update profile (PUT method)
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

Route::prefix('profile')->group(function() {
    Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth:customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/customer/profile', [CustomerController::class, 'showProfileForm'])->name('customer.profile');
    Route::put('/customer/profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');

});

Route::middleware(['auth:seller'])->group(function () {
    Route::get('/seller/dashboard', [SellerController::class, 'dashboard'])->name('seller.dashboard');
    Route::get('/seller/profile', [SellerController::class, 'editProfile'])->name('seller.profile');
    Route::put('/seller/profile', [SellerController::class, 'updateProfile'])->name('seller.profile.update');
    Route::get('/seller/profile/show', [SellerController::class, 'showProfile'])->name('seller.profile.show'); // opsional
});

Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::get('/track-order', [TrackController::class, 'show'])->name('track.track');

Route::get('/order', function () {
    return view('order.index');
})->name('order.index');

Route::get('/order/create', function () {
    return view('order.create');
})->name('order.create');

Route::get('/order/edit/{index}', function ($index) {
    return view('order.edit', ['index' => $index]);
})->name('order.edit');