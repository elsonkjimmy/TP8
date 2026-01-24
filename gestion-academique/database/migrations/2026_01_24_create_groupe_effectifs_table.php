<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('groupe_effectifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('groupe_id')->constrained()->cascadeOnDelete();
            $table->integer('annee')->comment('Année académique (ex: 2025)');
            $table->string('semestre')->comment('S1 ou S2');
            $table->integer('effectif')->comment('Nombre d\'étudiants');
            $table->timestamps();

            // Chaque groupe/année/semestre doit être unique
            $table->unique(['groupe_id', 'annee', 'semestre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groupe_effectifs');
    }
};
