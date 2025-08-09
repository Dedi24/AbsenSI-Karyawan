<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        $settings = $this->settingService->getAllAsArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name'      => 'required|string|max:255',
            'company_address'   => 'required|string',
            'company_email'     => 'required|email',
            'company_phone'     => 'required|string',
            'work_start_time'   => 'required|date_format:H:i',
            'work_end_time'     => 'required|date_format:H:i',
            'timezone'          => 'required|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
            'time_format'       => 'required|in:12,24',
            'office_location'   => 'required|string',
            'tolerance_radius'  => 'required|integer|min:50|max:1000',
            'company_logo'      => 'nullable|image|max:2048|mimes:jpg,jpeg,png,gif',
        ]);

        // Simpan logo jika ada
        if ($request->hasFile('company_logo')) {
            $path = $request->file('company_logo')->store('logos', 'public');
            $this->settingService->set('company_logo', '/storage/' . $path);
        }

        // Simpan semua setting
        $this->settingService->setMultiple([
            'company_name'        => $request->company_name,
            'company_address'     => $request->company_address,
            'company_email'       => $request->company_email,
            'company_phone'       => $request->company_phone,
            'work_start_time'     => $request->work_start_time . ':00',
            'work_end_time'       => $request->work_end_time . ':00',
            'timezone'            => $request->timezone,
            'time_format'         => $request->time_format,
            'office_location'     => $request->office_location,
            'tolerance_radius'    => $request->tolerance_radius,
            'whatsapp_group'      => $request->whatsapp_group ?? '',
        ]);

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}
