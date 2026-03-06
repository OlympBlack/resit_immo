<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrats', function (Blueprint $table) {

            $table->id();

            $table->foreignId('bien_id')
                  ->constrained('biens')
                  ->onDelete('cascade');

            $table->foreignId('locataire_id')
                  ->constrained('locataires')
                  ->onDelete('cascade');

            $table->date('date_debut');

            $table->date('date_fin')->nullable();

            $table->decimal('montant_mensuel',10,2);

            $table->enum('statut',['actif','termine','resilie'])
                  ->default('actif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};
