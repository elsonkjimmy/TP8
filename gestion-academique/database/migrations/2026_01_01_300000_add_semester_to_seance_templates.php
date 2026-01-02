<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('seance_templates', function (Blueprint $table) {
            // Add optional semester field to tag templates by semester (e.g., "Sem1", "Sem2", null for all)
            $table->string('semester')->nullable()->default(null)->after('end_time')->comment('Semester tag (e.g., Sem1, Sem2) for filtering during generation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seance_templates', function (Blueprint $table) {
            $table->dropColumn('semester');
        });
    }
};
