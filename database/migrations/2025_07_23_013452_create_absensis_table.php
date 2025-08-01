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
         Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('location_in')->nullable();
            $table->string('location_out')->nullable();
            // Pastikan kolom status cukup panjang untuk menyimpan 'terlambat'
            $table->enum('status', ['hadir', 'alpha', 'terlambat'])->default('alpha');
            // Kolom untuk menyimpan durasi kerja jika dihitung
            $table->decimal('working_hours', 5, 2)->nullable(); 
            $table->timestamps();

            // Index untuk pencarian cepat
            $table->unique(['user_id', 'date']);
            $table->index('date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensis');
    }
};
