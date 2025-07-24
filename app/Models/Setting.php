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

    // Method helper untuk format waktu Indonesia
    public static function formatTimeIndonesia($time)
    {
        if (!$time) return '-';

        try {
            $timeObj = \Carbon\Carbon::createFromFormat('H:i:s', $time);
            return $timeObj->format('H:i');
        } catch (\Exception $e) {
            return $time;
        }
    }

    // Method helper untuk format tanggal Indonesia
    public static function formatDateIndonesia($date)
    {
        if (!$date) return '-';

        try {
            $dateObj = \Carbon\Carbon::parse($date);
            return $dateObj->isoFormat('D MMMM YYYY');
        } catch (\Exception $e) {
            return $date;
        }
    }

    // Method helper untuk format tanggal pendek Indonesia
    public static function formatDateShortIndonesia($date)
    {
        if (!$date) return '-';

        try {
            $dateObj = \Carbon\Carbon::parse($date);
            return $dateObj->isoFormat('D MMM YYYY');
        } catch (\Exception $e) {
            return $date;
        }
    }
}
