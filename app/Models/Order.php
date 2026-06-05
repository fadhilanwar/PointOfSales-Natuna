<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    // Pastikan semua kolom yang ada di database terdaftar di sini
    protected $fillable = [
        'invoice_number',
        'user_id',
        'shipping_address',
        'order_source',
        'status',
        'rejection_reason',
        'payment_method',     // Kolom baru dari Fase Checkout
        'payment_proof_path', // Kolom untuk bukti transfer
        'courier_id',
        'grand_total',
    ];

    // Relasi ke User (Menggunakan withTrashed agar nama user tetap muncul di history meski akun dihapus)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    // Relasi ke Kurir
    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    // Relasi ke Detail Item Pesanan
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Mengubah default Route Model Binding dari 'id' menjadi 'invoice_number'
     */
    public function getRouteKeyName(): string
    {
        return 'invoice_number';
    }
}
