<?php

namespace Database\Seeders;

use App\Models\Locataire;
use Illuminate\Database\Seeder;

class LocataireSeeder extends Seeder
{
    public function run(): void
    {
        $locataires = [
            [
                'nom' => 'Durand',
                'prenom' => 'Alice',
                'email' => 'alice.durand@example.com',
                'telephone' => '07 12 34 56 78',
            ],
            [
                'nom' => 'Moreau',
                'prenom' => 'Kevin',
                'email' => 'kevin.moreau@example.com',
                'telephone' => '06 23 45 67 89',
            ],
            [
                'nom' => 'Petit',
                'prenom' => 'Camille',
                'email' => 'camille.petit@example.com',
                'telephone' => '07 34 56 78 90',
            ],
            [
                'nom' => 'Simon',
                'prenom' => 'Hugo',
                'email' => 'hugo.simon@example.com',
                'telephone' => '06 45 67 89 01',
            ],
            [
                'nom' => 'Laurent',
                'prenom' => 'Emma',
                'email' => 'emma.laurent@example.com',
                'telephone' => '07 56 78 90 12',
            ],
            [
                'nom' => 'Rousseau',
                'prenom' => 'Maxime',
                'email' => 'maxime.rousseau@example.com',
                'telephone' => '06 67 89 01 23',
            ],
        ];

        foreach ($locataires as $data) {
            Locataire::create($data);
        }
    }
}
