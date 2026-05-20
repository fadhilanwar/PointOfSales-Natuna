<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Pembelian ini berasal dari supplier mana
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Detail barang apa saja yang ada di dalam pembelian ini
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
