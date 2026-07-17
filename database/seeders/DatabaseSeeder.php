<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Buat admin user kalau belum ada
        User::firstOrCreate(
            ['email' => 'admin@syifaboxingcamp.com'],
            [
                'name'     => 'Admin Syifa',
                'password' => Hash::make('syifa@admin2026'),
            ]
        );
    }
}
