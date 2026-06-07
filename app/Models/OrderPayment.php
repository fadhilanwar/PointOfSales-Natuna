<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use HasFactory;

    // Daftarkan kolom yang boleh diisi
    protected $fillable = [
        'order_id',
        'amount',
        'type',
        'payment_method',
        'payment_proof_path',
        'status'
    ];

    // Relasi balik ke tabel orders (Pembayaran ini milik order yang mana)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
