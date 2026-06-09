<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Daftarkan kolom apa saja yang boleh diisi (mass assignment)
    protected $fillable = [
        'user_id',
        'invoice_number',
        'shipping_address',
        'courier_id',
        'grand_total',
        'delivery_status',
        'payment_status'
    ];

    // 1. Relasi ke tabel order_payments
    public function payments()
    {
        // Cukup gunakan satu return. ->latest() bagus agar urut dari yang paling baru bayar
        return $this->hasMany(OrderPayment::class)->latest();
    }

    // Relasi ke tabel order_items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // 2. Relasi ke tabel users 
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel kurir
    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    // 3. Accessor: Menghitung total uang yang SUDAH DI-ACC oleh Admin
    // Nanti di Controller/Blade, kita tinggal panggil: $order->total_paid
    public function getTotalPaidAttribute()
    {
        return $this->payments()
            ->where('status', 'approved')
            ->sum('amount');
    }

    // 4. Accessor BARU: Menghitung Sisa Tagihan (Hutang)
    // Sangat penting untuk fitur Buku Hutang dan Pelunasan
    public function getRemainingDebtAttribute()
    {
        // grand_total dikurangi uang yang sudah di-ACC
        $sisa = $this->grand_total - $this->total_paid;
        
        // Pastikan tidak minus. Jika minus/lunas, kembalikan 0.
        return $sisa > 0 ? $sisa : 0;
    }
}