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
        if (! Schema::hasTable('rapport_seances')) {
            Schema::create('rapport_seances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('seance_id')->constrained('seances')->onDelete('cascade');
                $table->foreignId('filled_by_id')->nullable()->constrained('users');
                $table->foreignId('validated_by_id')->nullable()->constrained('users');
                $table->text('contenu')->nullable();
                $table->string('status')->default('draft'); // draft | validated
                $table->timestamp('validated_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('rapport_seances')) {
            Schema::dropIfExists('rapport_seances');
        }
    }
};
