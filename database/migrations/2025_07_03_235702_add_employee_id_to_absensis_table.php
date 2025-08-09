<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Tambahkan ini

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom employee_id yang bisa NULL dulu, tanpa FK langsung
        Schema::table('absensis', function (Blueprint $table) {
            // Cek apakah kolom employee_id sudah ada. Jika belum, tambahkan.
            if (!Schema::hasColumn('absensis', 'employee_id')) {
                $table->unsignedBigInteger('employee_id')->nullable()->after('id');
            }
            // Tambahkan index untuk performa
            $table->index('employee_id');
        });

        // 2. Perbaiki data yang tidak konsisten
        //    a. Temukan employee_id yang tidak valid (0 atau tidak ada di employees)
        $validEmployeeIds = DB::table('employees')->pluck('id')->toArray();
        //    b. Hapus baris dengan employee_id yang tidak valid
        DB::table('absensis')->whereNotIn('employee_id', $validEmployeeIds)->where('employee_id', '!=', null)->delete();
        //    c. (Opsional) Jika ada kolom `user_id` yang bisa dipetakan ke `employee_id`, lakukan konversi
        //       Misalnya, jika `user_id` seharusnya menjadi `employee_id`:
        //       DB::table('absensis')->whereNull('employee_id')->update(['employee_id' => DB::raw('user_id')]);
        //       Tapi karena `user_id=2` juga tidak valid, kita hapus dulu baris ini di langkah b.

        // 3. Sekarang, tambahkan foreign key constraint karena semua data sudah valid
        Schema::table('absensis', function (Blueprint $table) {
            // Tambahkan constraint FK
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            // Jika kolom memang harus NOT NULL, ubah sekarang:
            // $table->unsignedBigInteger('employee_id')->nullable(false)->change();
            // Karena kita biarkan nullable untuk sementara, abaikan langkah ini.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            // Hapus foreign key constraint dulu
            $table->dropForeign(['employee_id']);
            // Hapus index
            $table->dropIndex(['employee_id']);
            // Hapus kolom jika memang perlu (atau biarkan saja)
            $table->dropColumn('employee_id'); // Komentari jika tidak ingin hapus kolom saat rollback
        });
    }
};
