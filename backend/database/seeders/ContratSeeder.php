<?php

namespace Database\Seeders;

use App\Models\Contrat;
use Illuminate\Database\Seeder;

class ContratSeeder extends Seeder
{
    public function run(): void
    {
        $contrats = [
            // Contrat actif — Alice Durand dans l'appart T3 Paris (bien 1)
            [
                'bien_id' => 1,
                'locataire_id' => 1,
                'date_debut' => '2025-01-01',
                'date_fin' => '2025-12-31',
                'montant_mensuel' => 1200.00,
                'statut' => 'actif',
            ],

            // Contrat actif — Kevin Moreau dans le studio Montmartre (bien 2)
            [
                'bien_id' => 2,
                'locataire_id' => 2,
                'date_debut' => '2025-03-01',
                'date_fin' => null,
                'montant_mensuel' => 650.00,
                'statut' => 'actif',
            ],

            // Contrat actif — Camille Petit dans la maison Lyon (bien 3)
            [
                'bien_id' => 3,
                'locataire_id' => 3,
                'date_debut' => '2025-06-01',
                'date_fin' => '2026-05-31',
                'montant_mensuel' => 950.00,
                'statut' => 'actif',
            ],

            // Contrat terminé — Hugo Simon dans l'appart Part-Dieu (bien 4) — ancien locataire
            [
                'bien_id' => 4,
                'locataire_id' => 4,
                'date_debut' => '2023-09-01',
                'date_fin' => '2024-08-31',
                'montant_mensuel' => 700.00,
                'statut' => 'termine',
            ],

            // Contrat actif — Emma Laurent dans l'appart Part-Dieu (bien 4) — nouveau locataire
            [
                'bien_id' => 4,
                'locataire_id' => 5,
                'date_debut' => '2024-09-01',
                'date_fin' => '2025-08-31',
                'montant_mensuel' => 720.00,
                'statut' => 'actif',
            ],

            // Contrat résilié — Maxime Rousseau dans la villa Marseille (bien 5)
            [
                'bien_id' => 5,
                'locataire_id' => 6,
                'date_debut' => '2024-01-01',
                'date_fin' => '2024-06-30',
                'montant_mensuel' => 1500.00,
                'statut' => 'resilie',
            ],

            // Contrat actif — Alice Durand dans l'appart Haussmann (bien 6, nouveau contrat)
            [
                'bien_id' => 6,
                'locataire_id' => 1,
                'date_debut' => '2026-01-01',
                'date_fin' => '2026-12-31',
                'montant_mensuel' => 2200.00,
                'statut' => 'actif',
            ],
        ];

        foreach ($contrats as $data) {
            Contrat::create($data);
        }
    }
}
