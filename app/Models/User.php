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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'device_fingerprint',
        'last_login_ip',
        'last_login_at',
        'profile_photo_path', // Tambahkan field ini
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
    ];

    // Method untuk mengecek apakah user adalah admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Method untuk mengecek apakah user adalah karyawan
    public function isKaryawan()
    {
        return $this->role === 'karyawan';
    }

    // Relasi dengan absensi
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
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

    // Accessor untuk mendapatkan URL foto profil
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path 
            ? asset('storage/' . $this->profile_photo_path) 
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    // Method untuk mengupload foto profil
    public function setProfilePhoto($photo)
    {
        if ($photo) {
            $path = $photo->store('profile-photos', 'public');
            $this->profile_photo_path = $path;
            $this->save();
        }
    }

    // Method untuk menghapus foto profil
    public function removeProfilePhoto()
    {
        if ($this->profile_photo_path) {
            \Storage::disk('public')->delete($this->profile_photo_path);
            $this->profile_photo_path = null;
            $this->save();
        }
    }
}