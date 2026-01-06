<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        Schema::disableForeignKeyConstraints();

        // Truncate test tables
        DB::table('rapport_seances')->truncate();
        DB::table('notifications')->truncate();

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
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
