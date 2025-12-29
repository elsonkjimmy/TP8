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
        Schema::create('seances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ue_id')->constrained('ues');
            $table->date('jour');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->foreignId('salle_id')->constrained('salles');
            $table->foreignId('groupe_id')->constrained('groupes');
            $table->foreignId('enseignant_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seances');
    }
};
