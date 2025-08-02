<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AbsensiNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'karyawan')->paginate(15);
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,karyawan',
        ]);

        $employee = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Kirim notifikasi ke admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AbsensiNotification([
                'type' => 'employee_created',
                'employee_name' => $employee->name,
                'email' => $employee->email,
                'role' => $employee->role,
                'date' => now()->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY')
            ]));
        }

        return redirect()->route('admin.employees.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function edit(User $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,karyawan',
        ]);

        $employee->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->password) {
            $employee->update(['password' => Hash::make($request->password)]);
        }

        // Kirim notifikasi ke admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AbsensiNotification([
                'type' => 'employee_updated',
                'employee_name' => $employee->name,
                'email' => $employee->email,
                'role' => $employee->role,
                'date' => now()->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY')
            ]));
        }

        return redirect()->route('admin.employees.index')->with('success', 'Karyawan berhasil diupdate.');
    }

    public function destroy(User $employee)
    {
        $employeeName = $employee->name;
        $employee->delete();

        // Kirim notifikasi ke admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AbsensiNotification([
                'type' => 'employee_deleted',
                'employee_name' => $employeeName,
                'date' => now()->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY')
            ]));
        }

        return redirect()->route('admin.employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }

    public function showDevice(User $employee)
    {
        return view('admin.employees.device', compact('employee'));
    }

    public function resetDevice(User $employee)
    {
        $employee->update([
            'device_fingerprint' => null,
            'last_login_ip' => null,
            'last_login_at' => null
        ]);

        // Kirim notifikasi ke admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AbsensiNotification([
                'type' => 'device_reset',
                'employee_name' => $employee->name,
                'date' => now()->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY')
            ]));
        }

        return redirect()->back()->with('success', 'Device fingerprint karyawan berhasil direset.');
    }
}