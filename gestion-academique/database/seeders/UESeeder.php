<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ue;
use App\Models\Filiere;
use App\Models\User;
use App\Models\Groupe;

class UESeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filieres = Filiere::with('groupes')->get();
        $teachers = User::where('role', 'teacher')->get();

        if ($teachers->isEmpty()) {
            return;
        }

        $ues = [
            ['code' => 'INF101', 'nom' => 'Algorithmique 1'],
            ['code' => 'INF102', 'nom' => 'Structure Machine'],
            ['code' => 'INF103', 'nom' => 'Système d\'exploitation 1'],
            ['code' => 'MAT101', 'nom' => 'Algèbre 1'],
            ['code' => 'INF201', 'nom' => 'Algorithmique 2'],
            ['code' => 'INF202', 'nom' => 'Base de données'],
            ['code' => 'INF203', 'nom' => 'Réseaux 1'],
            ['code' => 'INF301', 'nom' => 'Génie Logiciel'],
            ['code' => 'INF302', 'nom' => 'Intelligence Artificielle'],
            ['code' => 'INF303', 'nom' => 'Compilation'],
            ['code' => 'INF401', 'nom' => 'Sécurité Informatique'],
            ['code' => 'INF402', 'nom' => 'Systèmes Distribués'],
            ['code' => 'INF501', 'nom' => 'Recherche Opérationnelle'],
            ['code' => 'INF502', 'nom' => 'Big Data'],
        ];

        foreach ($filieres as $filiere) {
            $codeSuffix = substr($filiere->code, -2); // L1, L2, etc.
            
            // Map Level to Course Number Prefix
            $levelNum = match($codeSuffix) {
                'L1' => '1',
                'L2' => '2',
                'L3' => '3',
                'M1' => '4', // M1 courses usually start with 4
                'M2' => '5', // M2 courses usually start with 5
                default => null,
            };

            if (!$levelNum) continue;

            // Assign UEs based on level
            foreach ($ues as $ue) {
                // Check if UE code corresponds to the filiere level
                // e.g. INF101 for L1 (1), INF401 for M1 (4)
                if (str_contains($ue['code'], 'INF'.$levelNum) || str_contains($ue['code'], 'MAT'.$levelNum)) {
                     // Assign to a random teacher
                     $teacher = $teachers->random();
                     // Assign to the first group of the filiere
                     $groupe = $filiere->groupes->first();

                     if ($groupe) {
                        Ue::firstOrCreate(
                            ['code' => $ue['code']], // Check uniqueness on CODE only first to avoid global clash
                            [
                                'nom' => $ue['nom'],
                                'filiere_id' => $filiere->id,
                                'enseignant_id' => $teacher->id,
                                'groupe_id' => $groupe->id
                            ]
                        );
                     }
                }
            }
        }
    }
}
