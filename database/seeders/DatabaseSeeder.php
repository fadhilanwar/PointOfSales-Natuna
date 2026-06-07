<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. BUAT AKUN TETAP (Admin & User Demo)
        // ============================================================
        User::factory()->create([
            'name'      => 'Admin Natuna',
            'username'  => 'adminnatuna',
            'email'     => 'admin@natuna.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'shop_name' => 'Natuna Pusat',
            'address'   => 'Jl. Pusat Grosir Natuna No. 1',
        ]);

        User::factory()->create([
            'name'      => 'User Natuna',
            'username'  => 'user123',
            'email'     => 'user@gmail.com',
            'password'  => Hash::make('password'),
            'role'      => 'user',
            'shop_name' => 'Toko User',
            'address'   => 'Jl. Sukamaju Raya No.99',
        ]);

        // ============================================================
        // 2. BUAT DATA MASTER
        // ============================================================
        $users      = User::factory(10)->create();
        $categories = Category::factory(5)->create();
        $products   = Product::factory(20)->create();

        // ============================================================
        // 3. SKENARIO KERANJANG AKTIF (3 user sedang belanja)
        // ============================================================
        $usersWithCarts = $users->random(3);
        foreach ($usersWithCarts as $user) {
            $cart = Cart::create(['user_id' => $user->id]);

            $cartProducts = $products->random(rand(2, 3));
            foreach ($cartProducts as $product) {
                CartItem::create([
                    'cart_id'    => $cart->id,
                    'product_id' => $product->id,
                    'quantity'   => rand(1, 3),
                ]);
            }
        }

        // ============================================================
        // 4. SKENARIO ORDER (Disesuaikan ke struktur database baru)
        // ============================================================
        // Buat 10 order dengan status bervariasi
        $orders = Order::factory(10)->make()->each(function ($order) use ($users, $products) {

            // Assign ke user random dari 10 user yang dibuat
            $order->user_id = $users->random()->id;
            $order->save();

            // --- Buat Order Items ---
            $orderProducts = $products->random(rand(1, 4));
            $grandTotal = 0;

            foreach ($orderProducts as $product) {
                $qty      = rand(1, 5);
                $subtotal = $product->base_price * $qty;
                $grandTotal += $subtotal;

                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $product->id,
                    'quantity'           => $qty,
                    'cost_price_at_time' => $product->cost_price,
                    'price_at_time'      => $product->base_price,
                    'subtotal'           => $subtotal,
                ]);
            }

            // Update grand_total setelah semua item dihitung
            $order->update(['grand_total' => $grandTotal]);

            // --- Buat Order Payment sesuai kondisi order ---
            // Logika: Setiap order PASTI punya minimal 1 riwayat pembayaran

            if ($order->delivery_status === 'cancelled') {
                // Order dibatalkan: payment-nya di-rejected
                OrderPayment::create([
                    'order_id'           => $order->id,
                    'amount'             => $grandTotal,
                    'type'               => 'full_payment',
                    'payment_method'     => 'transfer',
                    'payment_proof_path' => null,
                    'status'             => 'rejected',
                ]);
            } elseif ($order->payment_status === 'lunas') {
                // Order sudah lunas (biasanya status delivered)
                // Simulasi: ada yang bayar langsung full, ada yang 2x cicil
                $isCicil = rand(0, 1); // Random: lunas sekaligus atau 2x cicil

                if ($isCicil) {
                    // Cicilan pertama (sudah di-approve)
                    $cicilan1 = intval($grandTotal * 0.5); // 50% dulu
                    OrderPayment::create([
                        'order_id'           => $order->id,
                        'amount'             => $cicilan1,
                        'type'               => 'installment',
                        'payment_method'     => 'transfer',
                        'payment_proof_path' => 'payment_proofs/dummy_proof.jpg',
                        'status'             => 'approved',
                    ]);

                    // Cicilan kedua / pelunasan (sudah di-approve)
                    OrderPayment::create([
                        'order_id'           => $order->id,
                        'amount'             => $grandTotal - $cicilan1, // Sisa tagihan
                        'type'               => 'installment',
                        'payment_method'     => 'transfer',
                        'payment_proof_path' => 'payment_proofs/dummy_proof.jpg',
                        'status'             => 'approved',
                    ]);
                } else {
                    // Bayar lunas sekaligus
                    OrderPayment::create([
                        'order_id'           => $order->id,
                        'amount'             => $grandTotal,
                        'type'               => 'full_payment',
                        'payment_method'     => rand(0, 1) ? 'transfer' : 'cod',
                        'payment_proof_path' => 'payment_proofs/dummy_proof.jpg',
                        'status'             => 'approved',
                    ]);
                }
            } else {
                // Order masih 'belum_lunas' (pending/processing/shipping)
                // Simulasi: ada yang sudah upload bukti (pending verifikasi), ada yang belum
                $sudahUpload = rand(0, 1);

                OrderPayment::create([
                    'order_id'           => $order->id,
                    'amount'             => $grandTotal,
                    'type'               => 'full_payment',
                    'payment_method'     => 'transfer',
                    'payment_proof_path' => $sudahUpload ? 'payment_proofs/dummy_proof.jpg' : null,
                    'status'             => 'pending', // Belum di-approve admin
                ]);
            }
        });

        // ============================================================
        // 5. SOFT DELETE 2 USER (uji fitur histori tetap aman)
        // ============================================================
        $users->random(2)->each(fn($u) => $u->delete());

        // ============================================================
        // 6. DATA REKENING BANK
        // ============================================================
        BankAccount::create([
            'bank_name'      => 'BCA',
            'account_number' => '1234567890',
            'account_name'   => 'PT NATUNA GROSIR UTAMA',
            'is_active'      => true,
        ]);

        BankAccount::create([
            'bank_name'      => 'Mandiri',
            'account_number' => '0987654321',
            'account_name'   => 'PT NATUNA GROSIR UTAMA',
            'is_active'      => true,
        ]);
    }
}
