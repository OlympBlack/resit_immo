<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * ⚠️ L'ordre est important à cause des clés étrangères :
     *  1. Users         (aucune dépendance)
     *  2. Proprietaires (aucune dépendance)
     *  3. Biens         (dépend de Proprietaires)
     *  4. Locataires    (aucune dépendance)
     *  5. Contrats      (dépend de Biens + Locataires)
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProprietaireSeeder::class,
            BienSeeder::class,
            LocataireSeeder::class,
            ContratSeeder::class,
        ]);
    }
}
