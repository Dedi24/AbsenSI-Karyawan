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
        'status', // hadir, alpha, izin, sakit
        'location_in',
        'location_out',
        'keterangan', // untuk izin/sakit
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i:s',
        'check_out' => 'datetime:H:i:s',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk mendapatkan jam kerja
    public function getWorkingHoursAttribute()
    {
        if ($this->check_in && $this->check_out && $this->status === 'hadir') {
            try {
                $in = \Carbon\Carbon::createFromFormat('H:i:s', $this->check_in);
                $out = \Carbon\Carbon::createFromFormat('H:i:s', $this->check_out);
                return $out->diffInHours($in);
            } catch (\Exception $e) {
                return 0;
            }
        }
        return 0;
    }

    // Accessor untuk format waktu masuk
    public function getCheckInFormattedAttribute()
    {
        if ($this->check_in) {
            try {
                return \Carbon\Carbon::createFromFormat('H:i:s', $this->check_in)->format('H:i');
            } catch (\Exception $e) {
                return $this->check_in;
            }
        }
        return '-';
    }

    // Accessor untuk format waktu pulang
    public function getCheckOutFormattedAttribute()
    {
        if ($this->check_out) {
            try {
                return \Carbon\Carbon::createFromFormat('H:i:s', $this->check_out)->format('H:i');
            } catch (\Exception $e) {
                return $this->check_out;
            }
        }
        return '-';
    }

    // Accessor untuk format tanggal lengkap (Indonesia)
    public function getDateFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->date)->isoFormat('D MMMM YYYY');
    }

    // Accessor untuk format tanggal pendek (Indonesia)
    public function getDateShortFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->date)->isoFormat('D MMM YYYY');
    }

    // Accessor untuk format hari (Indonesia)
    public function getDayNameAttribute()
    {
        return \Carbon\Carbon::parse($this->date)->isoFormat('dddd');
    }

    // Scope untuk filter berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    // Scope untuk filter berdasarkan range tanggal
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
