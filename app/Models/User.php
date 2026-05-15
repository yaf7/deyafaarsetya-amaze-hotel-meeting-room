<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'role', // 'admin' atau 'pelanggan' (meski pelanggan tidak login)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Kolom yang digunakan untuk autentikasi (login).
     * Laravel akan mencari user berdasarkan kolom ini.
     */
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    /**
     * Relasi: User (admin) bisa memiliki banyak reservasi.
     */
    public function reservations()
    {
        return $this->hasMany(\App\Models\Reservation::class, 'admin_id');
    }
}
