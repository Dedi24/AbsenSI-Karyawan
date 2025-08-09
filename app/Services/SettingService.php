<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    /**
     * Ambil nilai setting berdasarkan key
     */
    public function get(string $key, $default = null): ?string
    {
        return Setting::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Simpan atau update setting
     */
    public function set(string $key, $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Simpan banyak setting sekaligus
     */
    public function setMultiple(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }

    // ==============================
    // 🔹 Company Settings
    // ==============================

    public function getCompanyName(): string
    {
        return $this->get('company_name', 'PT. Nama Perusahaan');
    }

    public function getCompanyAddress(): string
    {
        return $this->get('company_address', 'Jl. Raya No. 123, Kota Anda');
    }

    public function getCompanyEmail(): string
    {
        return $this->get('company_email', 'info@perusahaan.com');
    }

    public function getCompanyPhone(): string
    {
        return $this->get('company_phone', '+62 812 3456 7890');
    }

    public function getCompanyLogo(): string
    {
        $logo = $this->get('company_logo');
        return $logo ?: '/images/logo-default.png'; // fallback
    }

    // ==============================
    // 🔹 Work & Time Settings
    // ==============================

    public function getWorkStartTime(): string
    {
        return substr($this->get('work_start_time', '08:00:00'), 0, 5); // Hanya HH:mm
    }

    public function getWorkEndTime(): string
    {
        return substr($this->get('work_end_time', '17:00:00'), 0, 5);
    }

    public function getTimezone(): string
    {
        return $this->get('timezone', 'Asia/Jakarta');
    }

    public function getTimeFormat(): string
    {
        return $this->get('time_format', '24');
    }

    // ==============================
    // 🔹 Location Settings
    // ==============================

    public function getOfficeLocation(): string
    {
        return $this->get('office_location', '-6.200000,106.816666');
    }

    public function getToleranceRadius(): int
    {
        return (int) $this->get('tolerance_radius', 100);
    }

    // ==============================
    // 🔹 Notification Settings
    // ==============================

    public function getWhatsAppGroup(): ?string
    {
        return $this->get('whatsapp_group');
    }

    // ==============================
    // 🔹 All Settings (untuk view)
    // ==============================

    public function getAllAsArray(): array
    {
        return [
            'company_name'        => $this->getCompanyName(),
            'company_address'     => $this->getCompanyAddress(),
            'company_email'       => $this->getCompanyEmail(),
            'company_phone'       => $this->getCompanyPhone(),
            'company_logo'        => $this->getCompanyLogo(),
            'work_start_time'     => $this->getWorkStartTime(),
            'work_end_time'       => $this->getWorkEndTime(),
            'timezone'            => $this->getTimezone(),
            'time_format'         => $this->getTimeFormat(),
            'office_location'     => $this->getOfficeLocation(),
            'tolerance_radius'    => $this->getToleranceRadius(),
            'whatsapp_group'      => $this->getWhatsAppGroup(),
        ];
    }
}
