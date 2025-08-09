<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee; // Menggunakan model Employee
use App\Notifications\AbsensiNotification; // Pastikan namespace ini benar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Untuk membuat slug pada nama file

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Employee::query(); // Menggunakan model Employee

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                // Tambahkan kolom lain untuk pencarian jika diperlukan
            });
        }

        // Mengambil data dengan paginasi
        $employees = $query->orderBy('name')->paginate(15)->appends(['search' => $search]);

        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        return view('admin.employees.create');
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // Data Pribadi
            'nik' => 'required|string|max:20|unique:employees',
            'nip' => 'nullable|string|max:20|unique:employees',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees',
            'no_hp' => 'nullable|string|max:20',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'status_perkawinan' => 'nullable|in:belum_kawin,kawin,cerai',
            'alamat' => 'nullable|string',
            'pendidikan_terakhir' => 'nullable|string|max:100',

            // Data Kepegawaian
            'jabatan' => 'nullable|string|max:100',
            'departemen' => 'nullable|string|max:100',
            'tanggal_masuk' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:karyawan,admin', // Validasi role

            // Akun
            'password' => 'required|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi foto
        ], [
            // Pesan error kustom jika diperlukan
            'nik.unique' => 'NIK ini sudah digunakan.',
            'nip.unique' => 'NIP ini sudah digunakan.',
            'email.unique' => 'Email ini sudah digunakan.',
        ]);

        // Proses upload foto jika ada
        $photoPath = null;
        if ($request->hasFile('photo')) {
            // Membuat nama file yang unik
            $originalName = pathinfo($request->file('photo')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileName = Str::slug($originalName) . '_' . time() . '.' . $extension;

            // Simpan foto ke direktori 'public/photos' (akan disimpan di storage/app/public/photos)
            $photoPath = $request->file('photo')->storeAs('photos', $fileName, 'public');
        }

        // Buat karyawan baru menggunakan model Employee
        $employee = Employee::create([
            // Data Pribadi
            'nik' => trim($validatedData['nik']),
            'nip' => trim($validatedData['nip'] ?? null),
            'name' => trim($validatedData['name']),
            'email' => strtolower(trim($validatedData['email'])),
            'no_hp' => trim($validatedData['no_hp'] ?? null),
            'tempat_lahir' => trim($validatedData['tempat_lahir'] ?? null),
            'tanggal_lahir' => $validatedData['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validatedData['jenis_kelamin'] ?? null,
            'status_perkawinan' => $validatedData['status_perkawinan'] ?? null,
            'alamat' => $validatedData['alamat'] ?? null,
            'pendidikan_terakhir' => trim($validatedData['pendidikan_terakhir'] ?? null),

            // Data Kepegawaian
            'jabatan' => trim($validatedData['jabatan'] ?? null),
            'departemen' => trim($validatedData['departemen'] ?? null),
            'tanggal_masuk' => $validatedData['tanggal_masuk'] ?? null,
            'status' => $validatedData['status'],
            'role' => $validatedData['role'],

            // Akun
            'password' => Hash::make($validatedData['password']), // Hash password
            'photo' => $photoPath, // Simpan path foto
        ]);

        // Kirim notifikasi ke admin (opsional)
        // Anda mungkin ingin membuat notifikasi khusus untuk pembuatan karyawan
        // $admins = Employee::where('role', 'admin')->get(); // Jika admin juga menggunakan model Employee
        // foreach ($admins as $admin) {
        //     $admin->notify(new AbsensiNotification([
        //         'type' => 'employee_created',
        //         'employee_name' => $employee->name,
        //         'email' => $employee->email,
        //         'role' => $employee->role,
        //         'date' => now()->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY')
        //     ]));
        // }

        return redirect()->route('admin.employees.index')
            ->with('success', 'Karyawan baru berhasil ditambahkan.');
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee) // Menggunakan model Employee
    {
        // Middleware 'auth' dan 'admin' sudah menangani autorisasi
        // Pengecekan role manual di sini tidak diperlukan lagi

        // Mengembalikan view dengan data karyawan
        // Semua accessor di model Employee akan otomatis tersedia di view
        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee) // Menggunakan model Employee
    {
        return view('admin.employees.edit', compact('employee'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee) // Menggunakan model Employee
    {
        $validatedData = $request->validate([
            // Data Pribadi
            'nik' => 'required|string|max:20|unique:employees,nik,' . $employee->id,
            'nip' => 'nullable|string|max:20|unique:employees,nip,' . $employee->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees,email,' . $employee->id,
            'no_hp' => 'nullable|string|max:20',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'status_perkawinan' => 'nullable|in:belum_kawin,kawin,cerai',
            'alamat' => 'nullable|string',
            'pendidikan_terakhir' => 'nullable|string|max:100',

            // Data Kepegawaian
            'jabatan' => 'nullable|string|max:100',
            'departemen' => 'nullable|string|max:100',
            'tanggal_masuk' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:karyawan,admin', // Validasi role

            // Akun
            'password' => 'nullable|string|min:8|confirmed', // Password bisa kosong jika tidak diubah
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi foto
        ]);

        // Data untuk diupdate
        $updateData = [
            // Data Pribadi
            'nik' => trim($validatedData['nik']),
            'nip' => trim($validatedData['nip'] ?? null),
            'name' => trim($validatedData['name']),
            'email' => strtolower(trim($validatedData['email'])),
            'no_hp' => trim($validatedData['no_hp'] ?? null),
            'tempat_lahir' => trim($validatedData['tempat_lahir'] ?? null),
            'tanggal_lahir' => $validatedData['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validatedData['jenis_kelamin'] ?? null,
            'status_perkawinan' => $validatedData['status_perkawinan'] ?? null,
            'alamat' => $validatedData['alamat'] ?? null,
            'pendidikan_terakhir' => trim($validatedData['pendidikan_terakhir'] ?? null),

            // Data Kepegawaian
            'jabatan' => trim($validatedData['jabatan'] ?? null),
            'departemen' => trim($validatedData['departemen'] ?? null),
            'tanggal_masuk' => $validatedData['tanggal_masuk'] ?? null,
            'status' => $validatedData['status'],
            'role' => $validatedData['role'],
        ];

        // Proses upload foto jika ada foto baru
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }

            // Membuat nama file yang unik
            $originalName = pathinfo($request->file('photo')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileName = Str::slug($originalName) . '_' . time() . '.' . $extension;

            // Simpan foto baru
            $photoPath = $request->file('photo')->storeAs('photos', $fileName, 'public');
            $updateData['photo'] = $photoPath;
        }

        // Update password hanya jika diisi
        if (!empty($validatedData['password'])) {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        // Update data karyawan
        $employee->update($updateData);

        // Kirim notifikasi ke admin (opsional)
        // $admins = Employee::where('role', 'admin')->get();
        // foreach ($admins as $admin) {
        //     $admin->notify(new AbsensiNotification([
        //         'type' => 'employee_updated',
        //         'employee_name' => $employee->name,
        //         'email' => $employee->email,
        //         'role' => $employee->role,
        //         'date' => now()->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY')
        //     ]));
        // }

        return redirect()->route('admin.employees.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee) // Menggunakan model Employee
    {
        $employeeName = $employee->name;

        // Hapus foto dari storage jika ada
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        // Hapus karyawan
        $employee->delete();

        // Kirim notifikasi ke admin (opsional)
        // $admins = Employee::where('role', 'admin')->get();
        // foreach ($admins as $admin) {
        //     $admin->notify(new AbsensiNotification([
        //         'type' => 'employee_deleted',
        //         'employee_name' => $employeeName,
        //         'date' => now()->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY')
        //     ]));
        // }

        return redirect()->route('admin.employees.index')
            ->with('success', "Karyawan '{$employeeName}' berhasil dihapus.");
    }

    /**
     * Show the device information for the specified employee.
     */
    public function showDevice(Employee $employee) // Menggunakan model Employee
    {
        // Middleware sudah menangani autorisasi
        return view('admin.employees.device', compact('employee'));
    }

    /**
     * Reset the device fingerprint for the specified employee.
     */
    public function resetDevice(Employee $employee) // Menggunakan model Employee
    {
        // Reset hanya field device fingerprint, IP, dan waktu login
        $employee->update([
            'device_fingerprint' => null,
            'last_login_ip' => null,
            'last_login_at' => null
        ]);

        // Kirim notifikasi ke admin (opsional)
        // $admins = Employee::where('role', 'admin')->get();
        // foreach ($admins as $admin) {
        //     $admin->notify(new AbsensiNotification([
        //         'type' => 'device_reset',
        //         'employee_name' => $employee->name,
        //         'date' => now()->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY')
        //     ]));
        // }

        return redirect()->back()->with('success', 'Device fingerprint karyawan berhasil direset.');
    }
}
