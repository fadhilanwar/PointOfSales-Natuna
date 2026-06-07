<?php

namespace App\Models;

use App\Models\OrderPayment;
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

    // 1. Relasi ke tabel order_payments (Satu order punya banyak riwayat pembayaran)
    public function payments()
    {
        return $this->hasMany(OrderPayment::class);
        return $this->hasMany(OrderPayment::class, 'order_id')->latest();;
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // 2. Relasi ke tabel users (Satu order dimiliki oleh satu user)
    public function user()
    {
        return $this->belongsTo(User::class);
        return $this->belongsTo(User::class, 'user_id');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    // 3. Accessor Buatan: Menghitung total uang yang SUDAH DI-ACC oleh Admin
    // Nanti di Controller/Blade, kita tinggal panggil: $order->total_paid
    public function getTotalPaidAttribute()
    {
        // Panggil relasi payments, cari yang statusnya 'approved', lalu jumlahkan kolom 'amount'
        return $this->payments()
            ->where('status', 'approved')
            ->sum('amount');
    }
}
