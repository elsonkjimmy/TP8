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
        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `seances` ADD COLUMN `status` varchar(191) NOT NULL DEFAULT 'scheduled' AFTER `enseignant_id`");
        } catch (\Exception $e) {
            // ignore errors (column may already exist)
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `seances` DROP COLUMN `status`");
        } catch (\Exception $e) {
            // ignore
        }
    }
};
