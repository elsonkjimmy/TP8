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
        Schema::table('rapport_seances', function (Blueprint $table) {
            // Check if 'statut' exists and 'status' does not
            if (Schema::hasColumn('rapport_seances', 'statut') && !Schema::hasColumn('rapport_seances', 'status')) {
                $table->renameColumn('statut', 'status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapport_seances', function (Blueprint $table) {
             if (Schema::hasColumn('rapport_seances', 'status') && !Schema::hasColumn('rapport_seances', 'statut')) {
                $table->renameColumn('status', 'statut');
            }
        });
    }
};
