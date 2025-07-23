<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Karyawan 1',
                'email' => 'karyawan1@example.com',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Karyawan 2',
                'email' => 'karyawan2@example.com',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
