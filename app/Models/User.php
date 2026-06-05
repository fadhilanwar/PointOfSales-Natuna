<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes; // Tambahkan SoftDeletes

    protected $fillable = [
        'name',
<<<<<<< HEAD
        'username',
        'shop_name',
=======
        'shop_name',
        'username',
>>>>>>> 389fc063fe66a2f5c75611c6aec9a4b193c835d3
        'email',
        'phone_number',
        'address',
        'password',
        'role',
        'profile_photo_path'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

  
}