<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rapport_seances', function (Blueprint $table) {
            $table->foreignId('chapter_id')->nullable()->after('seance_id')->constrained('chapters')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('rapport_seances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('chapter_id');
        });
    }
};
