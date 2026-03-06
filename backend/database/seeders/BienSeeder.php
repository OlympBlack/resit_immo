<?php

namespace Database\Seeders;

use App\Models\Bien;
use Illuminate\Database\Seeder;

class BienSeeder extends Seeder
{
    public function run(): void
    {
        $biens = [
            // Biens de Sophie Martin (proprietaire_id: 1)
            [
                'titre' => 'Appartement T3 — Centre Paris',
                'type' => 'appartement',
                'adresse' => '15 Rue de Rivoli',
                'ville' => 'Paris',
                'prix' => 1200.00,
                'proprietaire_id' => 1,
            ],
            [
                'titre' => 'Studio meublé — Montmartre',
                'type' => 'appartement',
                'adresse' => '7 Rue Lepic',
                'ville' => 'Paris',
                'prix' => 650.00,
                'proprietaire_id' => 1,
            ],

            // Biens de Luc Bernard (proprietaire_id: 2)
            [
                'titre' => 'Maison 4 pièces avec jardin',
                'type' => 'maison',
                'adresse' => '22 Chemin des Roses',
                'ville' => 'Lyon',
                'prix' => 950.00,
                'proprietaire_id' => 2,
            ],
            [
                'titre' => 'Appartement T2 — Part-Dieu',
                'type' => 'appartement',
                'adresse' => '3 Rue Garibaldi',
                'ville' => 'Lyon',
                'prix' => 720.00,
                'proprietaire_id' => 2,
            ],

            // Biens de Marie Dubois (proprietaire_id: 3)
            [
                'titre' => 'Villa avec piscine — Vieux-Port',
                'type' => 'maison',
                'adresse' => '45 Boulevard du Bompard',
                'ville' => 'Marseille',
                'prix' => 1500.00,
                'proprietaire_id' => 3,
            ],

            // Biens de Thomas Lefevre (proprietaire_id: 4)
            [
                'titre' => 'Appartement T4 — Haussmann',
                'type' => 'appartement',
                'adresse' => '10 Boulevard Haussmann',
                'ville' => 'Paris',
                'prix' => 2200.00,
                'proprietaire_id' => 4,
            ],
            [
                'titre' => 'Maison de ville — Pigalle',
                'type' => 'maison',
                'adresse' => '2 Rue Fontaine',
                'ville' => 'Paris',
                'prix' => 1800.00,
                'proprietaire_id' => 4,
            ],
        ];

        foreach ($biens as $data) {
            Bien::create($data);
        }
    }
}
