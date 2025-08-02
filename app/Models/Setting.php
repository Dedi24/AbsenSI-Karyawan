<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            // Handle jika value adalah string JSON
            if (is_string($setting->value)) {
                $decoded = json_decode($setting->value, true);
                return $decoded !== null ? $decoded : $setting->value;
            }
            return $setting->value;
        }
        return $default;
    }

    public static function set($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value]
        );
    }

    // Method helper untuk mendapatkan waktu kerja
    public static function getWorkStartTime()
    {
        $time = self::get('work_start_time', '08:00:00');
        return is_string($time) ? $time : '08:00:00';
    }

    public static function getWorkEndTime()
    {
        $time = self::get('work_end_time', '17:00:00');
        return is_string($time) ? $time : '17:00:00';
    }

    // Method helper untuk mendapatkan pengaturan lainnya
    public static function getCompanyName()
    {
        return self::get('company_name', 'Perusahaan Kita');
    }

    public static function getCompanyAddress()
    {
        return self::get('company_address', 'Jl. Perusahaan No. 123, Kota, Provinsi');
    }

    public static function getCompanyEmail()
    {
        return self::get('company_email', 'info@perusahaan.com');
    }

    public static function getCompanyPhone()
    {
        return self::get('company_phone', '+62 812 3456 7890');
    }

    public static function getCompanyLogo()
    {
        return self::get('company_logo', '/images/company-logo.png');
    }

    public static function getTimezone()
    {
        return self::get('timezone', 'Asia/Jakarta');
    }

    public static function getTimeFormat()
    {
        return self::get('time_format', '24');
    }

    public static function getOfficeLocation()
    {
        return self::get('office_location', '-6.200000,106.816666');
    }

    public static function getToleranceRadius()
    {
        return (int) self::get('tolerance_radius', 100);
    }

    public static function getWhatsAppGroup()
    {
        return self::get('whatsapp_group', '');
    }
}