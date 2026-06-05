<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Courier;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin Statis & 10 User Dummy
        User::factory()->create([
            'name' => 'Admin Natuna',
            'username' => 'adminnatuna',
            'email' => 'admin@natuna.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'shop_name' => 'Natuna Pusat', // Opsional untuk admin
            'address' => 'Jl. Pusat Grosir Natuna No. 1',
        ]);

        // Generate 10 pemilik warung/toko
        $users = User::factory(10)->create();

        // 2. Buat 5 Kurir & 20 Produk
        $couriers = Courier::factory(5)->create();
        $products = Product::factory(20)->create();

        // 3. Skenario Keranjang: 3 User memiliki Cart Aktif
        $usersWithCarts = $users->random(3);
        foreach ($usersWithCarts as $user) {
            $cart = Cart::create(['user_id' => $user->id]);

            // Masukkan 2-3 produk random ke keranjang
            $cartProducts = $products->random(rand(2, 3));
            foreach ($cartProducts as $product) {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 3)
                ]);
            }
        }

        // 4. Skenario Pesanan Selesai (Order & OrderItem)
        // Membuat 10 transaksi order
        $orders = Order::factory(10)->make()->each(function ($order) use ($users, $couriers, $products) {
            // Assign User
            $order->user_id = $users->random()->id;

            // Assign Courier jika statusnya shipping/completed
            if (in_array($order->status, ['shipping', 'completed'])) {
                $order->courier_id = $couriers->random()->id;
            }

            // Simpan header Order (grand_total masih 0)
            $order->save();

            // Pilih 1-4 produk random untuk dibeli
            $orderProducts = $products->random(rand(1, 4));
            $grandTotal = 0;

            foreach ($orderProducts as $product) {
                $qty = rand(1, 5);
                $subtotal = $product->base_price * $qty;
                $grandTotal += $subtotal;

                // Create Order Item (Disinilah Snapshot Harga terjadi!)
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'cost_price_at_time' => $product->cost_price, // Snapshot modal
                    'price_at_time' => $product->base_price,      // Snapshot jual
                    'subtotal' => $subtotal,
                ]);
            }

            // Update Grand Total setelah semua item dihitung
            $order->update(['grand_total' => $grandTotal]);
        });

        // 5. Skenario Uji Coba Soft Deletes
        // Hapus 2 user biasa secara acak (setelah mereka memiliki histori transaksi)
        $usersToSoftDelete = $users->random(2);
        foreach ($usersToSoftDelete as $deletedUser) {
            $deletedUser->delete();
            // Ini akan mengisi kolom deleted_at, tapi histori Order-nya di atas tetap utuh
        }

        // 1. Buat 1 Rekening Statis Utama (Pasti ada dan pasti aktif)
        BankAccount::create([
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_name' => 'PT NATUNA GROSIR UTAMA',
            'is_active' => true,
        ]);

        BankAccount::create([
            'bank_name' => 'Mandiri',
            'account_number' => '0987654321',
            'account_name' => 'PT NATUNA GROSIR UTAMA',
            'is_active' => true,
        ]);
    }
}
