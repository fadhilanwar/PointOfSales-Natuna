<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Satu supplier bisa memiliki banyak riwayat transaksi pembelian (PO)
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
