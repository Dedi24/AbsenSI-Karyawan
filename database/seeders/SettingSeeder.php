<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting; // Make sure this import is present

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use updateOrCreate to avoid duplicates
        // It checks for a record with the 'key' => 'company_name'
        // If found, it updates the 'value' and 'description'
        // If not found, it creates a new record

        Setting::updateOrCreate(
            ['key' => 'company_name'], // Attributes to match (unique)
            [ // Values to set or update
                'value' => 'PT Contoh Jaya Abadi', // Change this to your desired default company name
                'description' => 'Nama Perusahaan'
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'work_start_time'],
            [
                'value' => '08:00:00',
                'description' => 'Waktu Masuk Kerja (HH:MM:SS)'
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'work_end_time'],
            [
                'value' => '17:00:00',
                'description' => 'Waktu Pulang Kerja (HH:MM:SS)'
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'office_location'],
            [
                'value' => '-6.200000,106.816666', // Default to Jakarta coordinates, change as needed
                'description' => 'Koordinat Kantor (Latitude, Longitude)'
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'tolerance_radius'],
            [
                'value' => '100', // 100 meters tolerance
                'description' => 'Radius Toleransi Lokasi (meter)'
            ]
        );

         Setting::updateOrCreate(
            ['key' => 'whatsapp_group'],
            [
                'value' => '', // Initially empty, to be filled by admin
                'description' => 'ID Grup WhatsApp untuk Notifikasi'
            ]
        );
        // Add more settings as needed using the same pattern
    }
}