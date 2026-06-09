<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DebtController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController as KategoriController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
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
Route::get('/', function () {
    return view('index');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');

    // Rute untuk Update Qty (+ dan -)
    Route::patch('/keranjang/{cartItem}', [CartController::class, 'updateQuantity']);
    // HAPUS PRODUK di keranjang
    Route::delete('/keranjang/{cartItem}', [CartController::class, 'removeItem']);
    // Rute Tambah dari Home (yang kita buat sebelumnya)
    Route::post('/keranjang/add', [CartController::class, 'add'])->name('cart.add');

    // Menampilkan Halaman Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    /// Memproses Transaksi Checkout Awal
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    // Rute Beli Langsung
    Route::post('/direct-checkout', [CartController::class, 'directCheckout'])->name('cart.directCheckout');

    // Halaman Upload Bukti Pembayaran (Khusus Transfer)
    Route::get('/pembayaran/{order:invoice_number}', [CheckoutController::class, 'showPaymentPage'])->name('checkout.payment');
    // Proses Upload Bukti
    Route::post('/pembayaran/{order}/upload', [CheckoutController::class, 'uploadPaymentProof'])->name('checkout.payment.upload');

    // Daftar Pesanan
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    // Detail Pesanan 
    Route::get('/pesanan/{order:invoice_number}', [OrderController::class, 'show'])->name('orders.show');
    // Pelunasan: User upload bukti bayar tambahan dari halaman detail pesanan
    Route::post('/pesanan/{order:invoice_number}/bayar', [CheckoutController::class, 'storeAdditionalPayment'])
        ->name('orders.pay');

    // Halaman Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    // edit profil
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // edit password
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    // edit foto profil
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');

    Route::get('/produk/{product:slug}', [HomeController::class, 'show'])->name('products.show');

    // Rute Pencarian Produk
    Route::get('/cari', [HomeController::class, 'search'])->name('products.search');

    // Rute Kategori Produk
    Route::get('/kategori', [KategoriController::class, 'index'])->name('categories.index');
    Route::get('/kategori/{category:slug}', [KategoriController::class, 'show'])->name('categories.show');
});

Route::get('/admin/dashboard/filter', [DashboardController::class, 'filterData'])
    ->name('admin.dashboard.filter');


Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    

    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('couriers', CourierController::class);
    Route::resource('suppliers', SupplierController::class);

    //Transaksi
    Route::resource('purchases', PurchaseController::class);
    // Receive Barang
    Route::patch('purchases/{purchase}/receive', [PurchaseController::class, 'receiveItem'])->name('purchases.receive');

    // Kasir
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/cart/add', [PosController::class, 'addToCart'])->name('pos.cart.add');
    Route::post('/pos/cart/update', [PosController::class, 'updateCart'])->name('pos.cart.update');
    Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
    Route::get('pos/receipt/{id}', [App\Http\Controllers\Admin\PosController::class, 'receipt'])->name('pos.receipt');

    Route::get('transactions', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/{id}', [App\Http\Controllers\Admin\TransactionController::class, 'show'])->name('transactions.show');

    // Rute Atur Pengiriman & Kurir
    Route::patch('transactions/{id}/delivery', [App\Http\Controllers\Admin\TransactionController::class, 'updateDelivery'])->name('transactions.delivery');

    // Rute Validasi ACC/Tolak Bukti Transfer
    Route::patch('transactions/{id}/payment/verify/{payment_id}', [App\Http\Controllers\Admin\TransactionController::class, 'verifyPayment'])->name('transactions.verify_payment');

    // Rute Input Pembayaran Manual (Untuk COD Kasbon / Sisa Angsuran)
    Route::post('transactions/{id}/payment/manual', [App\Http\Controllers\Admin\TransactionController::class, 'addManualPayment'])->name('transactions.manual_payment');

    Route::get('/hutang', [DebtController::class, 'index'])->name('debts.index');
    Route::get('/hutang/{id}', [DebtController::class, 'show'])->name('debts.show');
});

Route::prefix('admin/report')->group(function () {
    Route::get('transaction', [App\Http\Controllers\Admin\ReportController::class, 'transactionReport'])->name('report.transaction');
    Route::get('transaction/{id}', [App\Http\Controllers\Admin\ReportController::class, 'showTransaction'])->name('report.transaction.show'); // Rute Baru
    
    Route::get('supply', [App\Http\Controllers\Admin\ReportController::class, 'supplyReport'])->name('report.supply');
    Route::get('supply/{id}', [App\Http\Controllers\Admin\ReportController::class, 'showSupply'])->name('report.supply.show');
});

