<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biens', function (Blueprint $table) {

            $table->id();

            $table->string('titre');

            $table->enum('type', ['maison','appartement']);

            $table->string('adresse');

            $table->string('ville')->nullable();

            $table->decimal('prix',10,2);

            $table->foreignId('proprietaire_id')
                  ->constrained('proprietaires')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biens');
    }
};
