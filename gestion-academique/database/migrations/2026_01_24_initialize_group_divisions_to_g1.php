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
        // Set all existing seances to G1 if group_divisions is null
        DB::table('seances')->whereNull('group_divisions')->update(['group_divisions' => 'G1']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset all seances group_divisions to null
        DB::table('seances')->where('group_divisions', 'G1')->update(['group_divisions' => null]);
    }
};
