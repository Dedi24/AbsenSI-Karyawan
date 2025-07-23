<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run()
    {
        DB::table('settings')->insert([
            [
                'key' => 'company_name',
                'value' => json_encode('Perusahaan Kita'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'work_start_time',
                'value' => json_encode('08:00:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'work_end_time',
                'value' => json_encode('17:00:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'office_location',
                'value' => json_encode('-6.200000,106.816666'), // Jakarta
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'tolerance_radius',
                'value' => json_encode(100), // meter
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'whatsapp_group',
                'value' => json_encode(''),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
