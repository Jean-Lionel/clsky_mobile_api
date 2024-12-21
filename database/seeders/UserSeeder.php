<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un administrateur
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@clsky.com',
            'password' => Hash::make('password123'),
            'role' => UserRole::ADMINISTRATEUR
        ]);

        // Créer des enquêteurs
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Enquêteur $i",
                'email' => "enqueteur$i@clsky.com",
                'password' => Hash::make('password123'),
                'role' => UserRole::ENQUETEUR
            ]);
        }

        // Créer des utilisateurs standards
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "User $i",
                'email' => "user$i@clsky.com",
                'password' => Hash::make('password123'),
                'role' => UserRole::UTILISATEUR
            ]);
        }
    }
}