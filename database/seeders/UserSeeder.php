<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gemilangwo.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '08123456789',
            'address' => 'Jakarta, Indonesia',
            'role' => 'admin',
        ]);

        // Owner User
        User::create([
            'name' => 'Owner Business',
            'email' => 'owner@gemilangwo.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '08234567890',
            'address' => 'Surabaya, Indonesia',
            'role' => 'owner',
        ]);

        // Customer Users
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gemilangwo.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '08111111111',
            'address' => 'Bandung, Jawa Barat',
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@gemilangwo.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '08222222222',
            'address' => 'Yogyakarta, DI Yogyakarta',
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Ahmad Wijaya',
            'email' => 'ahmad@gemilangwo.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '08333333333',
            'address' => 'Medan, Sumatera Utara',
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@gemilangwo.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '08444444444',
            'address' => 'Makassar, Sulawesi Selatan',
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Rinto Harahap',
            'email' => 'rinto@gemilangwo.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '08555555555',
            'address' => 'Malang, Jawa Timur',
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Nina Kusuma',
            'email' => 'nina@gemilangwo.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '08666666666',
            'address' => 'Semarang, Jawa Tengah',
            'role' => 'customer',
        ]);
    }
}
