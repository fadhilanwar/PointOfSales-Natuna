<?php

namespace App\Models;

use App\Models\Courier;
use App\Models\OrderItem;
use App\Models\StockMutation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number', // Biarkan tetap fillable
        'user_id',
        'shipping_address',
        'order_source',
        'status',
        'rejection_reason',
        'payment_proof_path',
        'courier_id',
        'grand_total',
    ];

    // --- TAMBAHKAN BLOK KODE INI ---
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // 1. Tentukan Prefix berdasarkan source
            $prefix = $order->order_source === 'pos' ? 'POS' : 'WEB';

            // 2. Dapatkan tanggal hari ini dengan format YYMMDD (contoh: 260424)
            $date = now()->format('ymd');

            // 3. Cari pesanan terakhir di hari yang sama dengan prefix yang sama
            // Gunakan lockForUpdate() jika aplikasinya memiliki traffic sangat tinggi (opsional)
            $lastOrder = static::where('invoice_number', 'LIKE', "{$prefix}-{$date}-%")
                ->orderBy('id', 'desc')
                ->first();

            // 4. Tentukan nomor urut berikutnya
            if ($lastOrder) {
                // Ambil 4 karakter terakhir, ubah ke integer, lalu tambah 1
                $lastSequence = (int) substr($lastOrder->invoice_number, -4);
                $nextSequence = $lastSequence + 1;
            } else {
                // Jika belum ada pesanan hari ini, mulai dari 1
                $nextSequence = 1;
            }

            // 5. Rangkai menjadi string final dengan padding 4 digit (0001, 0015, dst)
            $order->invoice_number = sprintf('%s-%s-%04d', $prefix, $date, $nextSequence);
        });
    }

    // Relasi: Milik siapa pesanan ini?
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Siapa kurirnya?
    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    // Relasi: Apa saja barang yang dibeli?
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Jika pesanan ini memicu mutasi stok
    public function stockMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class);
    }
}
