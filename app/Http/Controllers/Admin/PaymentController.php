<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // ============================================================
    // INDEX: Daftar semua pembayaran transfer yang perlu diverifikasi
    // Fokus pada payment_method = 'transfer' dan status = 'pending'
    // yang sudah ada bukti foto (payment_proof_path tidak null)
    // ============================================================
    public function index(Request $request)
    {
        // Ambil tab aktif dari query string, default 'menunggu'
        $activeTab = $request->get('tab', 'menunggu');

        // Base query: semua pembayaran transfer (bukan COD)
        $query = OrderPayment::with(['order.user'])
            ->where('payment_method', 'transfer')
            ->latest();

        // Filter berdasarkan tab yang dipilih admin
        if ($activeTab === 'menunggu') {
            // Menunggu = sudah ada bukti foto tapi belum diproses admin
            $query->where('status', 'pending')
                ->whereNotNull('payment_proof_path');
        } elseif ($activeTab === 'disetujui') {
            $query->where('status', 'approved');
        } elseif ($activeTab === 'ditolak') {
            $query->where('status', 'rejected');
        } elseif ($activeTab === 'belum_upload') {
            // Belum upload = pending tapi payment_proof_path masih null
            $query->where('status', 'pending')
                ->whereNull('payment_proof_path');
        }

        $payments = $query->paginate(15)->get();

        // Hitung badge count untuk tiap tab (agar admin tahu ada berapa yang perlu diproses)
        $countMenunggu   = OrderPayment::where('payment_method', 'transfer')
            ->where('status', 'pending')
            ->whereNotNull('payment_proof_path')
            ->count();
        $countBelumUpload = OrderPayment::where('payment_method', 'transfer')
            ->where('status', 'pending')
            ->whereNull('payment_proof_path')
            ->count();

        return view('admin.pages.payments.index', compact(
            'payments',
            'activeTab',
            'countMenunggu',
            'countBelumUpload'
        ));
    }

    // ============================================================
    // SHOW: Halaman detail satu pembayaran
    // Admin bisa lihat bukti foto lebih besar dan riwayat cicilan
    // ============================================================
    public function show(OrderPayment $payment)
    {
        // PERBAIKAN 1: Hapus tanda kutip kosong ('') di akhir array load
        $payment->load(['order.user', 'order.items.product', 'order.payments']);

        // Hitung total yang sudah di-approve sebelum payment ini
        $totalSudahApproved = $payment->order->payments()
            ->where('status', 'approved')
            ->sum('amount');

        $sisaTagihan = max(0, $payment->order->grand_total - $totalSudahApproved);

        // PERBAIKAN 2: Pastikan mengambil model Courier secara absolut jika belum di-use di atas
        $couriers = \App\Models\Courier::orderBy('name')->get();

        return view('admin.pages.payments.show', compact(
            'payment',
            'totalSudahApproved',
            'couriers',
            'sisaTagihan'
        ));
    }

    // ============================================================
    // APPROVE: Admin menyetujui pembayaran + isi amount manual
    //
    // ALUR LOGIKA:
    // 1. Validasi amount yang diisi admin (sesuai bukti foto)
    // 2. Update order_payments.status = 'approved' dan amount
    // 3. Hitung total semua amount yang approved untuk order ini
    // 4. Jika total >= grand_total → orders.payment_status = 'lunas'
    //    Jika total <  grand_total → orders.payment_status = 'belum_lunas'
    //                                order_payments.type = 'installment'
    // ============================================================
    public function approve(Request $request, OrderPayment $payment)
    {
        // Validasi: pastikan payment masih berstatus pending
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
        }

        // Validasi input amount dari admin
        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:1000', // Minimal Rp 1.000 agar tidak salah input
                // Amount tidak boleh melebihi sisa tagihan order
                function ($attribute, $value, $fail) use ($payment) {
                    $totalSudahApproved = $payment->order->payments()
                        ->where('status', 'approved')
                        ->sum('amount');
                    $sisaTagihan = $payment->order->grand_total - $totalSudahApproved;
                    if ($value > $sisaTagihan + 1) { // +1 untuk toleransi pembulatan
                        $fail('Nominal melebihi sisa tagihan sebesar Rp ' . number_format($sisaTagihan, 0, ',', '.'));
                    }
                }
            ],
        ], [
            'amount.required' => 'Nominal pembayaran wajib diisi.',
            'amount.numeric'  => 'Nominal harus berupa angka.',
            'amount.min'      => 'Nominal minimal Rp 1.000.',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update payment ini: status approved + amount sesuai input admin
            $payment->update([
                'status' => 'approved',
                'amount' => $request->amount,
            ]);

            // 2. Hitung ULANG total semua pembayaran approved untuk order ini
            //    (termasuk payment yang baru saja di-approve di atas)
            $order = $payment->order;
            $totalSudahApproved = $order->payments()
                ->where('status', 'approved')
                ->sum('amount');

            // 3. Tentukan tipe payment dan status order berdasarkan total yang sudah terbayar
            if ($totalSudahApproved >= $order->grand_total) {
                // KONDISI B (LUNAS): Total bayar sudah mencukupi grand_total
                $payment->update(['type' => 'full_payment']);
                $order->update(['payment_status' => 'lunas']);

                $pesan = 'Pembayaran disetujui. Pesanan ' . $order->invoice_number . ' sudah LUNAS.';
            } else {
                // KONDISI A (CICILAN): Total bayar masih kurang dari grand_total
                $payment->update(['type' => 'installment']);
                $order->update(['payment_status' => 'belum_lunas']);

                $sisaTagihan = $order->grand_total - $totalSudahApproved;
                $pesan = 'Pembayaran disetujui sebagai cicilan. Sisa tagihan: Rp ' . number_format($sisaTagihan, 0, ',', '.');
            }

            DB::commit();
            return redirect()->route('admin.payments.index')
                ->with('success', $pesan);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // ============================================================
    // REJECT: Admin menolak bukti pembayaran
    //
    // ALUR LOGIKA:
    // 1. Update order_payments.status = 'rejected'
    // 2. orders.payment_status tetap 'belum_lunas'
    // 3. User harus upload bukti baru (sistem akan buat entri baru di order_payments)
    // ============================================================
    public function reject(Request $request, OrderPayment $payment)
    {
        // Validasi: pastikan payment masih berstatus pending
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
        }

        // Update status menjadi rejected
        // Order.payment_status tetap 'belum_lunas' (tidak diubah)
        $payment->update([
            'status' => 'rejected',
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Bukti pembayaran ditolak. User akan diminta mengirim ulang bukti transfer.');
    }



    public function updateDelivery(Request $request, Order $order)
    {
        // Validasi input dari form Blade
        $request->validate([
            'courier_id'      => ['required', 'exists:couriers,id'],
            'delivery_status' => ['required', 'in:pending,processing,shipping,delivered,cancelled'],
        ], [
            'courier_id.required'      => 'Kurir wajib dipilih.',
            'courier_id.exists'        => 'Kurir yang dipilih tidak valid.',
            'delivery_status.required' => 'Status pengiriman wajib dipilih.',
            'delivery_status.in'       => 'Status pengiriman tidak valid.',
        ]);

        // Update hanya dua kolom — tidak ada logika tambahan
        $order->update([
            'courier_id'      => $request->courier_id,
            'delivery_status' => $request->delivery_status,
        ]);

        return redirect()->route('admin.payments.show', $order->id)
            ->with('success', 'Data pengiriman pesanan ' . $order->invoice_number . ' berhasil diperbarui.');
    }
}
