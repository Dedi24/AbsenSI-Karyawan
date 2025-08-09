<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique()->nullable();
            $table->string('nip')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('no_hp')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->enum('status_perkawinan', ['belum_kawin', 'kawin', 'cerai'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('departemen')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('role', ['admin', 'karyawan'])->default('karyawan');
            $table->string('device_fingerprint')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('photo')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
