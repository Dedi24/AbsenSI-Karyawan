<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'company_name' => Setting::getCompanyName(),
            'company_address' => Setting::getCompanyAddress(),
            'company_email' => Setting::getCompanyEmail(),
            'company_phone' => Setting::getCompanyPhone(),
            'company_logo' => Setting::getCompanyLogo(),
            'work_start_time' => Setting::getWorkStartTime(),
            'work_end_time' => Setting::getWorkEndTime(),
            'timezone' => Setting::getTimezone(),
            'time_format' => Setting::getTimeFormat(),
            'office_location' => Setting::getOfficeLocation(),
            'tolerance_radius' => Setting::getToleranceRadius(),
            'whatsapp_group' => Setting::getWhatsAppGroup(),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:500',
            'company_email' => 'required|email|max:255',
            'company_phone' => 'required|string|max:20',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'work_start_time' => 'required|date_format:H:i',
            'work_end_time' => 'required|date_format:H:i',
            'timezone' => 'required|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
            'time_format' => 'required|in:12,24',
            'office_location' => 'required|string',
            'tolerance_radius' => 'required|integer|min:50|max:1000',
            'whatsapp_group' => 'nullable|string',
        ]);

        // Simpan informasi perusahaan
        Setting::set('company_name', $request->company_name);
        Setting::set('company_address', $request->company_address);
        Setting::set('company_email', $request->company_email);
        Setting::set('company_phone', $request->company_phone);

        // Upload logo jika ada
        if ($request->hasFile('company_logo')) {
            $logo = $request->file('company_logo');
            $logoName = 'company-logo.' . $logo->getClientOriginalExtension();
            $logoPath = $logo->storeAs('public/logos', $logoName);
            Setting::set('company_logo', Storage::url($logoPath));
        }

        // Simpan pengaturan waktu kerja
        Setting::set('work_start_time', $request->work_start_time . ':00');
        Setting::set('work_end_time', $request->work_end_time . ':00');
        Setting::set('timezone', $request->timezone);
        Setting::set('time_format', $request->time_format);
        Setting::set('office_location', $request->office_location);
        Setting::set('tolerance_radius', $request->tolerance_radius);
        Setting::set('whatsapp_group', $request->whatsapp_group);

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}