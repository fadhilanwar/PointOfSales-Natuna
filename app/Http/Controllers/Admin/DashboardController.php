<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ============================================================
        // CARD 1: PENDAPATAN LUNAS BULAN INI
        // Logika: Jumlahkan amount dari order_payments yang:
        //   - status = 'approved' (sudah diverifikasi admin)
        //   - dibuat di bulan & tahun saat ini
        // Catatan: Ini bukan grand_total, tapi uang yang benar-benar masuk.
        // ============================================================
        $pendapatanBulanIni = OrderPayment::where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // ============================================================
        // CARD 2: TOTAL PIUTANG (Sisa hutang semua user yang belum lunas)
        // Logika: Ambil semua order yang payment_status = 'belum_lunas',
        //   lalu hitung: grand_total - total yang sudah dibayar (approved)
        // Menggunakan subquery untuk efisiensi, tanpa loop di PHP.
        // ============================================================
        $totalPiutang = Order::where('payment_status', 'belum_lunas')
            ->where('delivery_status', '!=', 'cancelled') // Order batal tidak dihitung piutang
            ->get()
            ->sum(function ($order) {
                // Hitung total bayar yang sudah di-approve untuk order ini
                $sudahDibayar = $order->payments()
                    ->where('status', 'approved')
                    ->sum('amount');
                return max(0, $order->grand_total - $sudahDibayar);
            });

        // ============================================================
        // CARD 3: JUMLAH PESANAN MENUNGGU DIPROSES
        // Logika: Hitung order yang delivery_status = 'pending'
        // Ini berarti pesanan sudah masuk tapi belum dikonfirmasi admin.
        // ============================================================
        $pesananMenunggu = Order::where('delivery_status', 'pending')->count();

        // ============================================================
        // CARD 4: TOTAL PRODUK AKTIF
        // Logika: Hitung produk yang masih aktif/tersedia di katalog.
        // Pakai is_active jika ada, atau hitung semua produk yang tidak soft-deleted.
        // ============================================================
        $totalProdukAktif = Product::count();
        // Kalau model Product kamu punya kolom is_active, ganti dengan:
        // $totalProdukAktif = Product::where('is_active', true)->count();

        // ============================================================
        // DATA CHART: PENJUALAN PER BULAN DI TAHUN INI (3 DATASET)
        // Kita siapkan 3 array data sekaligus agar filter JS bisa bekerja
        // tanpa perlu request ulang ke server.
        //
        // Dataset 1 (chartSemuaData)   : Semua order (lunas + belum lunas)
        // Dataset 2 (chartLunasData)   : Hanya grand_total dari order yang payment_status = 'lunas'
        // Dataset 3 (chartBelumLunas)  : Hanya grand_total dari order yang payment_status = 'belum_lunas'
        // ============================================================

        // Ambil data mentah: total grand_total per bulan, per payment_status, tahun ini
        $rawChartData = Order::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('payment_status'),
            DB::raw('SUM(grand_total) as total')
        )
            ->whereYear('created_at', now()->year)
            ->where('delivery_status', '!=', 'cancelled') // Abaikan order yang dibatalkan
            ->groupBy('bulan', 'payment_status')
            ->orderBy('bulan')
            ->get();

        // Inisialisasi array 12 bulan dengan nilai 0 untuk ketiga dataset
        $chartSemuaData  = array_fill(0, 12, 0);
        $chartLunasData  = array_fill(0, 12, 0);
        $chartBelumData  = array_fill(0, 12, 0);

        // Isi array berdasarkan data dari database
        foreach ($rawChartData as $row) {
            $index = $row->bulan - 1; // Bulan 1 (Januari) = index 0

            // Tambahkan ke dataset "semua" terlepas dari payment_status-nya
            $chartSemuaData[$index] += $row->total;

            if ($row->payment_status === 'lunas') {
                $chartLunasData[$index] += $row->total;
            } else {
                $chartBelumData[$index] += $row->total;
            }
        }

        // ============================================================
        // TABEL 1: TOP 5 PELANGGAN DENGAN HUTANG TERBESAR
        // Logika: Gabungkan users dan orders, filter yang belum lunas,
        //   lalu hitung sisa hutang per user.
        //   Menggunakan GROUP BY dan subquery untuk efisiensi.
        // ============================================================
        $topDebitur = User::select(
            'users.id',
            'users.name',
            'users.shop_name',
            DB::raw('SUM(orders.grand_total) as total_tagihan'),

            // Subquery: hitung total yang sudah dibayar (approved) per user
            DB::raw('(
                    SELECT COALESCE(SUM(op.amount), 0)
                    FROM order_payments op
                    JOIN orders o2 ON op.order_id = o2.id
                    WHERE o2.user_id = users.id
                    AND op.status = "approved"
                ) as total_terbayar')
        )
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->where('orders.payment_status', 'belum_lunas')
            ->where('orders.delivery_status', '!=', 'cancelled')
            ->groupBy('users.id', 'users.name', 'users.shop_name')
            // Hitung sisa hutang = total_tagihan - total_terbayar
            // HAVING dipakai karena kita filter hasil aggregate
            ->havingRaw('(SUM(orders.grand_total) - total_terbayar) > 0')
            ->orderByRaw('(SUM(orders.grand_total) - total_terbayar) DESC')
            ->limit(5)
            ->get()
            ->map(function ($user) {
                // Tambahkan computed field sisa_hutang agar mudah dipakai di blade
                $user->sisa_hutang = max(0, $user->total_tagihan - $user->total_terbayar);
                return $user;
            });

        // ============================================================
        // TABEL 2: TOP 5 PRODUK TERLARIS
        // Logika: Join order_items dengan products, lalu jumlahkan quantity
        //   yang terjual. Abaikan order yang dibatalkan.
        // ============================================================
        $topProduk = DB::table('order_items')
            ->select(
                'products.name as nama_produk',
                DB::raw('SUM(order_items.quantity) as total_terjual'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.delivery_status', '!=', 'cancelled') // Abaikan order batal
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // ============================================================
        // Kirim semua data ke view
        // ============================================================
        return view('admin.dashboard', compact(
            'pendapatanBulanIni',
            'totalPiutang',
            'pesananMenunggu',
            'totalProdukAktif',
            'chartSemuaData',
            'chartLunasData',
            'chartBelumData',
            'topDebitur',
            'topProduk'
        ));

    }
}
