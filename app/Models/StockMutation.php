<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'order_id', // Nullable (hanya diisi jika dari transaksi penjualan)
        'type',     // 'in' (Masuk) atau 'out' (Keluar)
        'quantity',
        'description',
    ];
}