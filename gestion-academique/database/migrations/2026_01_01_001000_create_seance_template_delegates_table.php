<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seance_template_delegates')) {
            Schema::create('seance_template_delegates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('seance_template_id')->constrained('seance_templates')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('seance_template_delegates')) {
            Schema::dropIfExists('seance_template_delegates');
        }
    }
};
