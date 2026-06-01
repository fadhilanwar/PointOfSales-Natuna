<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
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

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', function () {
        return view('admin.pages.dashboard');
    })->name('dashboard');

    // CRUD Data Master (Users, Kategori, Produk)
    // Route::resource otomatis membuatkan route untuk index, create, store, edit, update, dan destroy
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');

    // Rute untuk Update Qty (+ dan -)
    Route::patch('/keranjang/{cartItem}', [CartController::class, 'updateQuantity']);

    // PASTIKAN BARIS INI ADA UNTUK HAPUS PRODUK
    Route::delete('/keranjang/{cartItem}', [CartController::class, 'removeItem']);

    // Rute Tambah dari Home (yang kita buat sebelumnya)
    Route::post('/keranjang/add', [CartController::class, 'add'])->name('cart.add');

    // Rute Checkout
    Route::post('/checkout/process', [CartController::class, 'checkout'])->name('checkout.process');
});
