<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    public $timestamps = false;

    protected $fillable = ['key', 'value'];

    // ----------------------------------
    // 🔹 STATIC GETTER METHODS
    // ----------------------------------

    public static function getValue($key, $default = null)
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function getCompanyName()
    {
        return self::getValue('company_name', 'Perusahaan Saya');
    }

    public static function getCompanyAddress()
    {
        return self::getValue('company_address');
    }

    public static function getCompanyEmail()
    {
        return self::getValue('company_email');
    }

    public static function getCompanyPhone()
    {
        return self::getValue('company_phone');
    }

    public static function getCompanyLogo()
    {
        $logo = self::getValue('company_logo');
        return $logo ?: '/images/logo-default.png';
    }

    // 🔹 Jam Kerja
    public static function getWorkStartTime()
    {
        return self::getValue('work_start_time', '08:00:00');
    }

    public static function getWorkEndTime()
    {
        return self::getValue('work_end_time', '17:00:00');
    }

    public static function getTimezone()
    {
        return self::getValue('timezone', 'Asia/Jakarta');
    }

    public static function getTimeFormat()
    {
        return self::getValue('time_format', '24');
    }

    // 🔹 Lokasi
    public static function getOfficeLocation()
    {
        return self::getValue('office_location', '-6.200000,106.816666');
    }

    public static function getToleranceRadius()
    {
        return (int) self::getValue('tolerance_radius', 100);
    }

    // 🔹 Notifikasi
    public static function getWhatsAppGroup()
    {
        return self::getValue('whatsapp_group');
    }
}
