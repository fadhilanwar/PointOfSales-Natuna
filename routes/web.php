<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');

    // Rute untuk Update Qty (+ dan -)
    Route::patch('/keranjang/{cartItem}', [CartController::class, 'updateQuantity']);

    // PASTIKAN BARIS INI ADA UNTUK HAPUS PRODUK
    Route::delete('/keranjang/{cartItem}', [CartController::class, 'removeItem']);

    // Rute Tambah dari (yang kita buat sebelumnya)
    Route::post('/keranjang/add', [CartController::class, 'add'])->name('cart.add');

    // Menampilkan Halaman Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

    /// Memproses Transaksi Checkout Awal
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    // Halaman Upload Bukti Pembayaran (Khusus Transfer)
    Route::get('/pembayaran/{order}', [CheckoutController::class, 'showPaymentPage'])->name('checkout.payment');
    
    // Proses Upload Bukti
    Route::post('/pembayaran/{order}/upload', [CheckoutController::class, 'uploadPaymentProof'])->name('checkout.payment.upload');

    // Daftar Pesanan
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    
    // Detail Pesanan (Route ini yang kita buat sekarang)
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// ==========================================
// ROUTE ADMIN (Tambahkan blok ini)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', function () {
        // Sesuaikan dengan lokasi file Anda. Jika tadi memindahkan ke folder 'pages', gunakan 'pages.admin'
        return view('admin.pages.dashboard'); 
    })->name('dashboard');

    // Data Master (Otomatis membuat route index, create, store, edit, update, destroy)
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('couriers', CourierController::class);
    Route::resource('suppliers', SupplierController::class);

});