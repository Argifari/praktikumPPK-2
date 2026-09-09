<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat 1 akun Admin
        User::create([
            'name' => 'Dian',
            'email' => 'dian@jara.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Membuat 1 akun User biasa
        User::create([
            'name' => 'Argi',
            'email' => 'argi@jara.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }
}
