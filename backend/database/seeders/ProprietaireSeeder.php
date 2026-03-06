<?php

namespace Database\Seeders;

use App\Models\Proprietaire;
use Illuminate\Database\Seeder;

class ProprietaireSeeder extends Seeder
{
    public function run(): void
    {
        $proprietaires = [
            [
                'nom' => 'Martin',
                'prenom' => 'Sophie',
                'email' => 'sophie.martin@example.com',
                'telephone' => '06 12 34 56 78',
                'adresse' => '12 Rue de la Paix, 75001 Paris',
            ],
            [
                'nom' => 'Bernard',
                'prenom' => 'Luc',
                'email' => 'luc.bernard@example.com',
                'telephone' => '06 98 76 54 32',
                'adresse' => '5 Avenue des Fleurs, 69001 Lyon',
            ],
            [
                'nom' => 'Dubois',
                'prenom' => 'Marie',
                'email' => 'marie.dubois@example.com',
                'telephone' => '07 11 22 33 44',
                'adresse' => '3 Impasse des Lilas, 13001 Marseille',
            ],
            [
                'nom' => 'Lefevre',
                'prenom' => 'Thomas',
                'email' => 'thomas.lefevre@example.com',
                'telephone' => '06 55 66 77 88',
                'adresse' => '8 Boulevard Haussmann, 75009 Paris',
            ],
        ];

        foreach ($proprietaires as $data) {
            Proprietaire::create($data);
        }
    }
}
