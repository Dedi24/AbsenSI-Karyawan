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
        'check_in' => 'datetime:H:i:s',
        'check_out' => 'datetime:H:i:s',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
        return '0';
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
        return '0';
    }

}
