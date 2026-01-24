<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add `group_divisions` column to seances and seance_templates
     * to track which group division(s) attend: G1, G2, or G1,G2
     */
    public function up(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            $table->string('group_divisions')->nullable()->comment("Divisions concerned: 'G1', 'G2', or 'G1,G2'")->after('groupe_id');
        });

        Schema::table('seance_templates', function (Blueprint $table) {
            $table->string('group_divisions')->nullable()->comment("Divisions concerned: 'G1', 'G2', or 'G1,G2'")->after('groupe_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            $table->dropColumn('group_divisions');
        });

        Schema::table('seance_templates', function (Blueprint $table) {
            $table->dropColumn('group_divisions');
        });
    }
};
