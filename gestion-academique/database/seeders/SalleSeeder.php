<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Salle;

class SalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salles = [
            ['numero' => 'Amphi 100', 'capacite' => 100],
            ['numero' => 'Amphi 200', 'capacite' => 200],
            ['numero' => 'Amphi 350', 'capacite' => 350],
            ['numero' => 'Amphi 500', 'capacite' => 500],
            ['numero' => 'Salle 101', 'capacite' => 50],
            ['numero' => 'Salle 102', 'capacite' => 50],
            ['numero' => 'Salle 103', 'capacite' => 50],
            ['numero' => 'Salle 201', 'capacite' => 60],
            ['numero' => 'Salle 202', 'capacite' => 60],
            ['numero' => 'Labo 1', 'capacite' => 30],
            ['numero' => 'Labo 2', 'capacite' => 30],
            ['numero' => 'Labo 3', 'capacite' => 30],
        ];

        foreach ($salles as $salle) {
            Salle::firstOrCreate(
                ['numero' => $salle['numero']],
                ['capacite' => $salle['capacite']]
            );
        }
    }
}
