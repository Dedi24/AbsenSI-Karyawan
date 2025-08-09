<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'action',
        'description',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'employee_id' => 'integer',
    ];

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Scope untuk filter berdasarkan user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope untuk filter berdasarkan employee
    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }

    // Scope untuk filter berdasarkan tanggal range
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Scope untuk filter berdasarkan action
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    // Accessor untuk format tanggal
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at ? \Carbon\Carbon::parse($this->created_at)->isoFormat('D MMMM YYYY H:mm') : '-';
    }

    // Accessor untuk nama user
    public function getUserFullNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return 'System';
    }

    // Accessor untuk nama employee
    public function getEmployeeNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->name;
        }
        return '-';
    }

    // Accessor untuk tipe log
    public function getLogTypeAttribute()
    {
        if ($this->user) {
            return 'admin';
        } elseif ($this->employee) {
            return 'employee';
        }
        return 'system';
    }

    // Method untuk mencatat aktivitas
    public static function log($action, $description = '', $userId = null, $employeeId = null, $ipAddress = null, $userAgent = null)
    {
        return static::create([
            'user_id' => $userId,
            'employee_id' => $employeeId,
            'action' => $action,
            'description' => $description,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
        ]);
    }

    // Method untuk mencatat login
    public static function logLogin($userId = null, $employeeId = null)
    {
        return static::log(
            'login',
            'User login attempt',
            $userId,
            $employeeId,
            request()->ip(),
            request()->userAgent()
        );
    }

    // Method untuk mencatat logout
    public static function logLogout($userId = null, $employeeId = null)
    {
        return static::log(
            'logout',
            'User logout',
            $userId,
            $employeeId,
            request()->ip(),
            request()->userAgent()
        );
    }

    // Method untuk mencatat absensi
    public static function logAttendance($employeeId, $action, $description = '')
    {
        return static::log(
            $action,
            $description,
            null,
            $employeeId,
            request()->ip(),
            request()->userAgent()
        );
    }

    // Method untuk mendapatkan log terbaru
    public static function getLatest($limit = 10)
    {
        return static::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    // Method untuk mendapatkan log berdasarkan jenis
    public static function getByType($type, $limit = 10)
    {
        $query = static::orderBy('created_at', 'desc')->limit($limit);

        switch ($type) {
            case 'admin':
                $query->whereNotNull('user_id');
                break;
            case 'employee':
                $query->whereNotNull('employee_id');
                break;
            case 'system':
                $query->whereNull('user_id')->whereNull('employee_id');
                break;
        }

        return $query->get();
    }

    // Method untuk mendapatkan statistik log
    public static function getStats($startDate, $endDate)
    {
        $logs = static::whereBetween('created_at', [$startDate, $endDate])->get();

        $stats = [
            'total' => $logs->count(),
            'admin' => $logs->whereNotNull('user_id')->count(),
            'employee' => $logs->whereNotNull('employee_id')->count(),
            'system' => $logs->whereNull('user_id')->whereNull('employee_id')->count(),
            'actions' => $logs->groupBy('action')->map->count()->toArray(),
        ];

        return $stats;
    }
}
