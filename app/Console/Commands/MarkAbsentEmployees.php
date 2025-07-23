<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\User;
use App\Models\Attendance;
use App\Notifications\AttendanceNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class MarkAbsentEmployees extends Command
{
    protected $signature = 'attendance:mark-absent';
    protected $description = 'Mark absent employees automatically';

    public function handle()
    {
        $today = Carbon::today();
        $employees = User::where('role', 'karyawan')->get();

        foreach ($employees as $employee) {
            $attendance = Absensi::firstOrCreate(
                ['user_id' => $employee->id, 'date' => $today],
                ['status' => 'alpha']
            );

            if ($attendance->wasRecentlyCreated) {
                // Kirim notifikasi ke admin
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new AbsensiNotification([
                        'type' => 'alpha',
                        'employee_name' => $employee->name,
                        'date' => $today->format('d M Y')
                    ]));
                }
            }
        }

        $this->info('Absent employees marked successfully.');
    }
}
