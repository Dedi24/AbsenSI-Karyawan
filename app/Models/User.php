<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'device_token',
        'device_fingerprint',
        'last_login_ip',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKaryawan()
    {
        return $this->role === 'karyawan';
    }

        // Method untuk memeriksa apakah device diizinkan
    public function isDeviceAllowed($deviceFingerprint)
    {
        // Device fingerprint, izinkan dan simpan
        if (!$this->device_fingerprint) {
            $this->update([
                'device_fingerprint' => $deviceFingerprint,
                'last_login_ip' => request()->ip(),
                'last_login_at' => now()
            ]);
            return true;
        }

        // Jika sudah ada, cek apakah sesuai
        return $this->device_fingerprint === $deviceFingerprint;
    }

    // Method untuk reset device fingerprint
    public function resetDeviceFingerprint()
    {
        return $this->update([
            'device_fingerprint' => null,
            'last_login_ip' => null,
            'last_login_at' => null
        ]);
    }
}
