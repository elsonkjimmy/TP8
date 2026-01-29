<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Groupe;
use App\Models\Filiere;

class GroupeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filieres = Filiere::all();

        foreach ($filieres as $filiere) {
            // Create 1 main group per filiere for now
            Groupe::firstOrCreate(
                ['nom' => 'Groupe A', 'filiere_id' => $filiere->id],
                []
            );
            
            // For L1 and L2, create more groups usually
            if (str_contains($filiere->code, 'L1') || str_contains($filiere->code, 'L2')) {
                 Groupe::firstOrCreate(
                    ['nom' => 'Groupe B', 'filiere_id' => $filiere->id],
                    []
                );
            }
        }
    }
}
