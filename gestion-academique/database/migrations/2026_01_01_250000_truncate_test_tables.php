<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Disable foreign key checks to allow truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate test tables
        DB::table('rapport_seances')->truncate();
        DB::table('notifications')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This is a data cleanup migration; nothing to revert
    }
};
