<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\ActivityLog;

class KaryawanController extends Controller
{
    // Menampilkan profil karyawan
    public function profileShow()
    {
        $employee = Auth::guard('employee')->user();
        return view('karyawan.profile.show', compact('employee'));
    }

    // Menampilkan form edit profil karyawan
    public function profileEdit()
    {
        $employee = Auth::guard('employee')->user();
        return view('karyawan.profile.edit', compact('employee'));
    }

    // Memperbarui profil karyawan
    public function profileUpdate(Request $request)
    {
        $employee = Auth::guard('employee')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('employees')->ignore($employee->id)],
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'confirmed|min:8',
            ]);
            $employee->password = Hash::make($request->password);
        }

        $employee->update($request->only(['name', 'email', 'no_hp', 'alamat']));

        ActivityLog::log('update_profile', "Profil karyawan diperbarui: {$employee->name}", null, $employee->id);

        return redirect()->route('karyawan.profile.show')->with('success', 'Profil berhasil diperbarui');
    }
}
