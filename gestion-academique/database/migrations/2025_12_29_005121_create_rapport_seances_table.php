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
        Schema::create('rapport_seances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seance_id')->constrained('seances');
            $table->foreignId('enseignant_id')->constrained('users');
            $table->foreignId('delegue_id')->constrained('users');
            $table->text('contenu');
            $table->string('statut');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapport_seances');
    }
};
