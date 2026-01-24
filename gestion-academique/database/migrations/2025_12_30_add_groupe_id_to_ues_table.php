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
        Schema::table('ues', function (Blueprint $table) {
            $table->foreignId('groupe_id')->nullable()->after('filiere_id')->constrained('groupes')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ues', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['groupe_id']);
            $table->dropColumn('groupe_id');
        });
    }
};
