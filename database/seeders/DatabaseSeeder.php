<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Akun Default untuk Testing

        // Akun Admin
        User::create([
            'name' => 'Administrator IT',
            'email' => 'admin@helpdesk.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Akun Teknisi 1
        User::create([
            'name' => 'Budi (Teknisi Hardware)',
            'email' => 'budi.tech@helpdesk.com',
            'password' => Hash::make('password123'),
            'role' => 'technician',
        ]);

        // Akun Teknisi 2
        User::create([
            'name' => 'Siti (Teknisi Jaringan)',
            'email' => 'siti.tech@helpdesk.com',
            'password' => Hash::make('password123'),
            'role' => 'technician',
        ]);

        // Akun User / Karyawan
        User::create([
            'name' => 'Andi Karyawan',
            'email' => 'andi@helpdesk.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // Seed Kategori Masalah Utama
        $categories = [
            'Perangkat Keras (Hardware)',
            'Perangkat Lunak (Software)',
            'Jaringan & Internet',
            'Akun & Otentikasi',
            'Lain-lain',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
            ]);
        }
    }
}