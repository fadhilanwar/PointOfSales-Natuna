<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // 2. Tambahkan trait SoftDeletes ke dalam class
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke Pesanan (Dari Fase 1 & 2)
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Relasi ke Keranjang (Dari Fase 3)
    // Ingat: Satu user biasanya hanya punya satu keranjang aktif pada satu waktu
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }
}
