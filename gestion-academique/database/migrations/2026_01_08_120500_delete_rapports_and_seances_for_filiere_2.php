<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DeleteRapportsAndSeancesForFiliere2 extends Migration
{
    /**
     * Run the migrations.
     * This will delete all `rapport_seances` and `seances` whose groupe belongs to filiere_id = 2.
     * WARNING: destructive operation — irreversible.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $seanceIds = DB::table('seances')
                ->select('seances.id')
                ->join('groupes', 'seances.groupe_id', '=', 'groupes.id')
                ->where('groupes.filiere_id', 2)
                ->pluck('id')
                ->toArray();

            if (!empty($seanceIds)) {
                DB::table('rapport_seances')->whereIn('seance_id', $seanceIds)->delete();
                DB::table('seances')->whereIn('id', $seanceIds)->delete();
            }
        });
    }

    /**
     * Reverse the migrations.
     * Irreversible: this migration deletes data and cannot restore it.
     *
     * @return void
     */
    public function down()
    {
        // Intentionally left empty because deleted data cannot be restored by this migration.
    }
}
