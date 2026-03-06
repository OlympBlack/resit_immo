<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Utilisateur admin / démo
        User::create([
            'name' => 'Admin RESIT',
            'email' => 'admin@resit-immo.fr',
            'password' => Hash::make('password123'),
        ]);

        // Utilisateur test supplémentaire
        User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
