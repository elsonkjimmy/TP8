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
        Schema::create('seance_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filiere_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('groupe_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('salle_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('enseignant_id')->nullable()->constrained('users')->nullOnDelete();
            $table->tinyInteger('day_of_week')->comment('1=Monday .. 6=Saturday');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seance_templates');
    }
};
