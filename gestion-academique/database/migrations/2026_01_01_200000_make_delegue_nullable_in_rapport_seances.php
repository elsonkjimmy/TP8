<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * We use a raw statement to avoid requiring doctrine/dbal for column changes.
     *
     * @return void
     */
    public function up()
    {
        // Make delegue_id nullable if the column exists
        try {
            $columns = DB::select("SHOW COLUMNS FROM `rapport_seances` LIKE 'delegue_id'");
            if (!empty($columns)) {
                DB::statement("ALTER TABLE `rapport_seances` MODIFY `delegue_id` BIGINT UNSIGNED NULL;");
            }
        } catch (\Throwable $e) {
            // ignore on environments where altering fails; developer can adjust manually
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            $columns = DB::select("SHOW COLUMNS FROM `rapport_seances` LIKE 'delegue_id'");
            if (!empty($columns)) {
                DB::statement("ALTER TABLE `rapport_seances` MODIFY `delegue_id` BIGINT UNSIGNED NOT NULL;");
            }
        } catch (\Throwable $e) {
            // ignore
        }
    }
};
