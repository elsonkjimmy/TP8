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
        Schema::create('demandes_modifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enseignant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seance_id')->nullable()->constrained('seances')->cascadeOnDelete();
            $table->foreignId('seance_template_id')->nullable()->constrained('seance_templates')->cascadeOnDelete();
            $table->enum('type_demande', ['créneau', 'salle', 'enseignant', 'autre'])->default('autre');
            $table->text('description');
            $table->enum('status', ['soumis', 'accepté', 'rejeté'])->default('soumis');
            $table->text('admin_response')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_modifications');
    }
};
