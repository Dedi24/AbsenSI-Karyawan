<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // misal: company_name, timezone
            $table->text('value');           // nilai konfigurasi
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert(
            [
                ['key' => 'company_name', 'value' => 'Nama Perusahaan Anda'],
                ['key' => 'company_address', 'value' => 'Alamat lengkap perusahaan'],
                ['key' => 'company_email', 'value' => 'info@perusahaan.com'],
                ['key' => 'company_phone', 'value' => '+62 812 3456 7890'],
                ['key' => 'company_logo', 'value' => '/images/logo-default.png'],

                ['key' => 'work_start_time', 'value' => '08:00:00'],
                ['key' => 'work_end_time', 'value' => '17:00:00'],
                ['key' => 'timezone', 'value' => 'Asia/Jakarta'],
                ['key' => 'time_format', 'value' => '24'],

                ['key' => 'office_location', 'value' => '-6.200000,106.816666'],
                ['key' => 'tolerance_radius', 'value' => '100'],

                ['key' => 'whatsapp_group', 'value' => ''],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
