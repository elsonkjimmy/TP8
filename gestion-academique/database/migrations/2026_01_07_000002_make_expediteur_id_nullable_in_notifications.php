<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            $columns = DB::select("SHOW COLUMNS FROM `notifications` LIKE 'expediteur_id'");
            if (!empty($columns)) {
                DB::statement("ALTER TABLE `notifications` MODIFY `expediteur_id` BIGINT UNSIGNED NULL;");
            }
        } catch (\Throwable $e) {
            // ignore on environments where altering fails
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            $columns = DB::select("SHOW COLUMNS FROM `notifications` LIKE 'expediteur_id'");
            if (!empty($columns)) {
                DB::statement("ALTER TABLE `notifications` MODIFY `expediteur_id` BIGINT UNSIGNED NOT NULL;");
            }
        } catch (\Throwable $e) {
            // ignore
        }
    }
};
