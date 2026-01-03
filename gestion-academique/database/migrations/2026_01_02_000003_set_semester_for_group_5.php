<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('seance_templates')
            ->where('groupe_id', 5)
            ->whereNull('semester')
            ->update(['semester' => 'S1']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('seance_templates')
            ->where('groupe_id', 5)
            ->where('semester', 'S1')
            ->update(['semester' => null]);
    }
};
