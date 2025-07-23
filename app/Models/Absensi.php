<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'location_in',
        'location_out',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk mendapatkan jam kerja
    public function getWorkingHoursAttribute()
    {
        if ($this->check_in && $this->check_out) {
            // Konversi waktu ke format yang bisa dihitung
            $checkInTime = substr($this->check_in, 0, 5);
            $checkOutTime = substr($this->check_out, 0, 5);

            // Parse waktu
            $checkIn = \DateTime::createFromFormat('H:i', $checkInTime);
            $checkOut = \DateTime::createFromFormat('H:i', $checkOutTime);

            if ($checkIn && $checkOut) {
                // Jika jam pulang lebih kecil dari jam masuk (melewati tengah malam)
                if ($checkOut < $checkIn) {
                    $checkOut->modify('+1 day');
                }

                $interval = $checkIn->diff($checkOut);
                $hours = $interval->h;
                $minutes = $interval->i;

                if ($hours > 0) {
                    return $hours . 'h ' . $minutes . 'm';
                } else {
                    return $minutes . 'm';
                }
            }
        }
        return '-';
    }

    // Accessor untuk format waktu masuk
    public function getCheckInFormattedAttribute()
    {
        if ($this->check_in) {
            return substr($this->check_in, 0, 5);
        }
        return '-';
    }

    // Accessor untuk format waktu pulang
    public function getCheckOutFormattedAttribute()
    {
        if ($this->check_out) {
            return substr($this->check_out, 0, 5);
        }
        return '-';
    }
}
