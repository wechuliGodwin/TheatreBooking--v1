<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'wechualigoddy@gmail.com'],
            [
                'name' => 'Godwin',
                'password' => Hash::make('password'),
                'role' => 'surgeon',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'ictintern007@kijabehospital.org'],
            [
                'name' => 'Annonymous User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
