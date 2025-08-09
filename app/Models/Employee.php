<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nik',
        'nip',
        'name',
        'email',
        'password',
        'no_hp',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_perkawinan',
        'alamat',
        'pendidikan_terakhir',
        'jabatan',
        'departemen',
        'tanggal_masuk',
        'status',
        'role',
        'device_fingerprint',
        'last_login_ip',
        'last_login_at',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'last_login_at' => 'datetime',
    ];

    // Accessor untuk format tanggal lahir
    public function getTanggalLahirFormattedAttribute()
    {
        return $this->tanggal_lahir ? \Carbon\Carbon::parse($this->tanggal_lahir)->isoFormat('D MMMM YYYY') : '-';
    }

    // Accessor untuk format tanggal masuk
    public function getTanggalMasukFormattedAttribute()
    {
        return $this->tanggal_masuk ? \Carbon\Carbon::parse($this->tanggal_masuk)->isoFormat('D MMMM YYYY') : '-';
    }

    // Accessor untuk format last login
    public function getLastLoginAtFormattedAttribute()
    {
        return $this->last_login_at ? \Carbon\Carbon::parse($this->last_login_at)->isoFormat('D MMMM YYYY H:mm') : '-';
    }

    // Accessor untuk format device fingerprint
    public function getDeviceFingerprintShortAttribute()
    {
        return $this->device_fingerprint ? substr($this->device_fingerprint, 0, 10) . '...' : '-';
    }

    // Accessor untuk format photo
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/' . $this->photo) : asset('images/default-avatar.png');
    }

    // Relasi dengan absensi
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'employee_id');
    }

    // Scope untuk filter berdasarkan status
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope untuk filter berdasarkan role
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Scope untuk filter berdasarkan device status
    public function scopeWithDevice($query)
    {
        return $query->where('device_fingerprint', '!=', null);
    }

    // Scope untuk filter berdasarkan tanpa device
    public function scopeWithoutDevice($query)
    {
        return $query->where('device_fingerprint', null);
    }

    // Method untuk mengecek apakah karyawan adalah admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Method untuk mengecek apakah karyawan adalah karyawan biasa
    public function isKaryawan()
    {
        return $this->role === 'karyawan';
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

    // Method untuk generate device fingerprint
    public function generateDeviceFingerprint($request)
    {
        $employeeAgent = $request->employeeAgent();
        $ipAddress = $request->ip();
        $acceptLanguage = $request->header('Accept-Language');
        $deviceInfo = $employeeAgent . $ipAddress . $acceptLanguage;
        return hash('sha256', $deviceInfo);
    }

    // Method untuk update last login
    public function updateLastLogin($request)
    {
        return $this->update([
            'last_login_ip' => $request->ip(),
            'last_login_at' => now(),
            'device_fingerprint' => $this->device_fingerprint ?? $this->generateDeviceFingerprint($request)
        ]);
    }

    // Accessor untuk mendapatkan usia
    public function getAgeAttribute()
    {
        return $this->tanggal_lahir ? \Carbon\Carbon::parse($this->tanggal_lahir)->age : '-';
    }

    // Accessor untuk mendapatkan masa kerja
    public function getMasaKerjaAttribute()
    {
        return $this->tanggal_masuk ? \Carbon\Carbon::parse($this->tanggal_masuk)->diffForHumans() : '-';
    }

    // Accessor untuk mendapatkan jumlah absensi bulan ini
    public function getAbsensiBulanIniAttribute()
    {
        return $this->absensis()->whereMonth('date', now()->month)->count();
    }

    // Accessor untuk mendapatkan jumlah keterlambatan bulan ini
    public function getKeterlambatanBulanIniAttribute()
    {
        $workStartTime = \App\Models\Setting::getWorkStartTime('08:00:00');
        return $this->absensis()
            ->whereMonth('date', now()->month)
            ->where('status', 'hadir')
            ->whereTime('check_in', '>', $workStartTime)
            ->count();
    }

    // Accessor untuk mendapatkan jumlah alpha bulan ini
    public function getAlphaBulanIniAttribute()
    {
        return $this->absensis()
            ->whereMonth('date', now()->month)
            ->where('status', 'alpha')
            ->count();
    }

    // Accessor untuk mendapatkan jumlah izin/sakit bulan ini
    public function getIzinSakitBulanIniAttribute()
    {
        return $this->absensis()
            ->whereMonth('date', now()->month)
            ->whereIn('status', ['izin', 'sakit'])
            ->count();
    }

    // Accessor untuk mendapatkan total jam kerja bulan ini
    public function getTotalJamKerjaBulanIniAttribute()
    {
        return $this->absensis()
            ->whereMonth('date', now()->month)
            ->sum('working_hours');
    }

    // Accessor untuk mendapatkan rata-rata jam kerja per hari
    public function getRataRataJamKerjaAttribute()
    {
        $totalHariKerja = $this->absensis()
            ->whereMonth('date', now()->month)
            ->where('status', 'hadir')
            ->count();
        if ($totalHariKerja > 0) {
            $totalJamKerja = $this->total_jam_kerja_bulan_ini;
            return round($totalJamKerja / $totalHariKerja, 1);
        }
        return 0;
    }

    // Accessor untuk mendapatkan persentase kehadiran bulan ini
    public function getPresentaseKehadiranAttribute()
    {
        $totalHariKerja = now()->daysInMonth;
        $totalHadir = $this->absensi_bulan_ini;
        if ($totalHariKerja > 0) {
            return round(($totalHadir / $totalHariKerja) * 100, 1);
        }
        return 0;
    }

    // Accessor untuk mendapatkan status device
    public function getDeviceStatusAttribute()
    {
        return $this->device_fingerprint ? 'terdaftar' : 'belum_terdaftar';
    }

    // Accessor untuk mendapatkan warna badge berdasarkan status
    public function getStatusBadgeColorAttribute()
    {
        switch ($this->status) {
            case 'active':
                return 'success';
            case 'inactive':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    // Accessor untuk mendapatkan warna badge berdasarkan role
    public function getRoleBadgeColorAttribute()
    {
        switch ($this->role) {
            case 'admin':
                return 'primary';
            case 'karyawan':
                return 'success';
            default:
                return 'secondary';
        }
    }

    // Accessor untuk mendapatkan icon berdasarkan jenis kelamin
    public function getJenisKelaminIconAttribute()
    {
        switch ($this->jenis_kelamin) {
            case 'L':
                return 'bi bi-gender-male';
            case 'P':
                return 'bi bi-gender-female';
            default:
                return 'bi bi-gender-ambiguous';
        }
    }

    // Accessor untuk mendapatkan status perkawinan dalam bahasa Indonesia
    public function getStatusPerkawinanFormattedAttribute()
    {
        switch ($this->status_perkawinan) {
            case 'belum_kawin':
                return 'Belum Kawin';
            case 'kawin':
                return 'Kawin';
            case 'cerai':
                return 'Cerai';
            default:
                return $this->status_perkawinan ?? '-';
        }
    }

    // Accessor untuk mendapatkan pendidikan terakhir dalam format singkat
    public function getPendidikanTerakhirSingkatAttribute()
    {
        return $this->pendidikan_terakhir ? substr($this->pendidikan_terakhir, 0, 20) . '...' : '-';
    }

    // Accessor untuk mendapatkan jabatan dalam format singkat
    public function getJabatanSingkatAttribute()
    {
        return $this->jabatan ? substr($this->jabatan, 0, 20) . '...' : '-';
    }

    // Accessor untuk mendapatkan departemen dalam format singkat
    public function getDepartemenSingkatAttribute()
    {
        return $this->departemen ? substr($this->departemen, 0, 20) . '...' : '-';
    }

    // Accessor untuk mendapatkan alamat dalam format singkat
    public function getAlamatSingkatAttribute()
    {
        return $this->alamat ? substr($this->alamat, 0, 50) . '...' : '-';
    }

    // Accessor untuk mendapatkan nomor telepon dalam format internasional
    public function getNoHpFormattedAttribute()
    {
        if ($this->no_hp) {
            // Format nomor telepon Indonesia
            if (substr($this->no_hp, 0, 1) === '0') {
                return '+62 ' . substr($this->no_hp, 1);
            } elseif (substr($this->no_hp, 0, 2) === '62') {
                return '+' . $this->no_hp;
            }
            return $this->no_hp;
        }
        return '-';
    }

    // Accessor untuk mendapatkan inisial nama
    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        return $initials;
    }

    // Accessor untuk mendapatkan avatar color berdasarkan inisial
    public function getAvatarColorAttribute()
    {
        $colors = ['primary', 'success', 'info', 'warning', 'danger'];
        $initials = $this->initials;
        $hash = 0;
        for ($i = 0; $i < strlen($initials); $i++) {
            $hash = ord($initials[$i]) + (($hash << 5) - $hash);
        }
        $index = abs($hash) % count($colors);
        return $colors[$index];
    }
}
