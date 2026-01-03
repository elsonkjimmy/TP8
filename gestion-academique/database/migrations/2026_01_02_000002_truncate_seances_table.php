<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Safely remove dependent records first to avoid FK constraint errors
        // Delete reports and absences referencing seances, then delete seances
        DB::table('rapport_seances')->delete();
        DB::table('absence_enseignants')->delete();
        DB::table('seances')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: we won't restore deleted data on rollback
        // This is a data cleanup migration
    }
};
