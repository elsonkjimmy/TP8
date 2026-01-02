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
        // Assign S1 to existing seances with NULL semester
        DB::table('seances')
            ->whereNull('semester')
            ->update(['semester' => 'S1']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to NULL if needed
        DB::table('seances')
            ->where('semester', 'S1')
            ->update(['semester' => null]);
    }
};
