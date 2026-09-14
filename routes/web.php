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
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductInquiryController;
use App\Http\Controllers\SellerInquiryController;
use App\Http\Controllers\ImpactFactorController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\VoucherController;
Route::get('/', function () {
    return redirect()->route('customer.dashboard');
});

# Halaman Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/seller/login', [LoginController::class, 'showSellerLoginForm'])->name('seller.login');
Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');
Route::post('/seller/login', [LoginController::class, 'authenticateSeller'])->name('seller.login.submit');
Route::post('/admin/login', [LoginController::class, 'authenticateAdmin'])->name('admin.login.submit');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

# Halaman Register Customer
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

# Halaman Reset Password
Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.request');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');

// Proteksi route customer (termasuk akses register seller)
Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
Route::get('/katalog', [CustomerController::class, 'catalog'])->name('catalog.index');
Route::get('/community', [CustomerController::class, 'community'])->name('community.index');
Route::get('/community/{story:slug}', [CustomerController::class, 'communityShow'])->name('community.show');

// Kirim komentar/pertanyaan pada cerita komunitas (khusus member terdaftar)
Route::middleware(['auth:customer'])->post('/community/{story:slug}/comments', [CustomerController::class, 'storeComment'])->name('community.comments.store');

Route::get('/seller/register', [SellerRegisterController::class, 'showRegistrationForm'])->name('seller.register.form');
Route::post('/seller/register', [SellerRegisterController::class, 'register'])->name('seller.register.submit');

// Proteksi route admin dengan prefix 'admin'
Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

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

    // ===== Khusus Super Admin: kelola staff/admin =====
    Route::middleware(['super_admin'])->group(function () {
        Route::get('/staff', [AdminController::class, 'staff'])->name('admin.staff');
        Route::post('/staff', [AdminController::class, 'storeAdmin'])->name('admin.staff.store');
        Route::put('/staff/{id}/role', [AdminController::class, 'updateAdminRole'])->name('admin.staff.role');
        Route::delete('/staff/{id}', [AdminController::class, 'destroyAdmin'])->name('admin.staff.destroy');

        // Registrasi admin baru (halaman lama)
        Route::get('/register', [LoginController::class, 'showAdminRegistrationForm'])->name('admin.register');
        Route::post('/register', [LoginController::class, 'registerAdmin'])->name('admin.register.submit');

        // Kelola faktor dampak per material
        Route::get('/impact-factors', [ImpactFactorController::class, 'index'])->name('admin.impact.index');
        Route::post('/impact-factors', [ImpactFactorController::class, 'store'])->name('admin.impact.store');
        Route::put('/impact-factors/{id}', [ImpactFactorController::class, 'update'])->name('admin.impact.update');
        Route::delete('/impact-factors/{id}', [ImpactFactorController::class, 'destroy'])->name('admin.impact.destroy');

        // Kelola voucher & reward
        Route::get('/vouchers', [VoucherController::class, 'index'])->name('admin.vouchers.index');
        Route::post('/vouchers', [VoucherController::class, 'store'])->name('admin.vouchers.store');
        Route::get('/vouchers/{id}/edit', [VoucherController::class, 'edit'])->name('admin.vouchers.edit');
        Route::put('/vouchers/{id}', [VoucherController::class, 'update'])->name('admin.vouchers.update');
        Route::delete('/vouchers/{id}', [VoucherController::class, 'destroy'])->name('admin.vouchers.destroy');
        Route::post('/vouchers/{id}/toggle', [VoucherController::class, 'toggle'])->name('admin.vouchers.toggle');
    });
});

# Proteksi route seller dan semua resource-nya
Route::middleware(['auth:seller'])->group(function () {
    Route::get('/seller/dashboard', [SellerController::class, 'dashboard'])->name('seller.dashboard');

    // Resource product
    Route::resource('products', ProductController::class)->except(['show']);
    Route::delete('products/{id}/gallery-image', [ProductController::class, 'deleteGalleryImage'])->name('products.gallery.delete');
    Route::delete('products/{id}/main-image', [ProductController::class, 'deleteMainImage'])->name('products.main.delete');
    Route::resource('order', OrderController::class)->except(['show']);
});

// Katalog dan detail produk dapat dilihat guest tanpa login.
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

Route::middleware(['auth:customer'])->group(function () {
    Route::get('/customer/profile', [CustomerController::class, 'showProfileForm'])->name('customer.profile');
    Route::put('/customer/profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');

    // Chat / pertanyaan produk ke admin
    Route::get('/customer/inquiries', [ProductInquiryController::class, 'index'])->name('customer.inquiries.index');
    Route::get('/customer/inquiries/{id}', [ProductInquiryController::class, 'show'])->name('customer.inquiries.show');
    Route::post('/customer/inquiries/{id}/reply', [ProductInquiryController::class, 'reply'])->name('customer.inquiries.reply');
    Route::post('/products/{product}/inquiries', [ProductInquiryController::class, 'store'])->name('customer.inquiries.store');

});

Route::middleware(['auth:seller'])->group(function () {
    Route::get('/seller/dashboard', [SellerController::class, 'dashboard'])->name('seller.dashboard');
    Route::get('/seller/profile', [SellerController::class, 'editProfile'])->name('seller.profile');
    Route::put('/seller/profile', [SellerController::class, 'updateProfile'])->name('seller.profile.update');
    Route::get('/seller/profile/show', [SellerController::class, 'showProfile'])->name('seller.profile.show'); // opsional

    // Chat / pertanyaan produk dari customer
    Route::get('/seller/inquiries', [SellerInquiryController::class, 'index'])->name('seller.inquiries.index');
    Route::get('/seller/inquiries/{id}', [SellerInquiryController::class, 'show'])->name('seller.inquiries.show');
    Route::post('/seller/inquiries/{id}/reply', [SellerInquiryController::class, 'reply'])->name('seller.inquiries.reply');
});

Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart/items', [CartController::class, 'store'])->name('cart.items.store');
Route::delete('/cart/items-selected', [CartController::class, 'destroySelected'])->name('cart.items.destroySelected');
Route::patch('/cart/items/{product}', [CartController::class, 'update'])->name('cart.items.update');
Route::delete('/cart/items/{product}', [CartController::class, 'destroy'])->name('cart.items.destroy');
Route::middleware(['auth:customer'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/preview', [CheckoutController::class, 'preview'])->name('checkout.preview');
    Route::get('/track-order', [TrackController::class, 'show'])->name('track.track');

    // Dompet koin sirkular & voucher
    Route::get('/customer/wallet', [WalletController::class, 'index'])->name('customer.wallet');
    Route::post('/customer/wallet/claim/{voucher}', [WalletController::class, 'claim'])->name('customer.wallet.claim');
});

