<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SaldoKas;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Cek dulu apakah user admin sudah ada
        if (!User::where('email', 'admin@admin.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
        }

        // Cek apakah saldo kas sudah ada
        if (!SaldoKas::exists()) {
            SaldoKas::create(['saldo' => 0]);
        }
    }
}
