<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'company_name' => Setting::get('company_name', 'Perusahaan Kita'),
            'work_start_time' => Setting::get('work_start_time', '08:00:00'),
            'work_end_time' => Setting::get('work_end_time', '17:00:00'),
            'office_location' => Setting::get('office_location', '-6.200000,106.816666'), // Jakarta
            'tolerance_radius' => Setting::get('tolerance_radius', 100), // meter
            'whatsapp_group' => Setting::get('whatsapp_group', ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'work_start_time' => 'required|date_format:H:i',
            'work_end_time' => 'required|date_format:H:i',
            'office_location' => 'required|string',
            'tolerance_radius' => 'required|integer|min:50|max:1000',
            'whatsapp_group' => 'nullable|string',
        ]);

        Setting::set('company_name', $request->company_name);
        Setting::set('work_start_time', $request->work_start_time . ':00'); // Tambahkan detik
        Setting::set('work_end_time', $request->work_end_time . ':00'); // Tambahkan detik
        Setting::set('office_location', $request->office_location);
        Setting::set('tolerance_radius', $request->tolerance_radius);
        Setting::set('whatsapp_group', $request->whatsapp_group);

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
