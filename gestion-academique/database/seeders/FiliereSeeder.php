<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filiere;
use App\Models\User;

class FiliereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = ['L1', 'L2', 'L3', 'M1', 'M2'];
        $teachers = User::where('role', 'teacher')->get();

        foreach ($levels as $index => $level) {
            // Assign a random teacher as responsible if available
            $responsible = $teachers->isNotEmpty() ? $teachers->random()->id : null;

            Filiere::firstOrCreate(
                ['code' => 'INFO-' . $level],
                [
                    'nom' => 'Informatique ' . $level,
                    'enseignant_responsable_id' => $responsible,
                ]
            );
        }
    }
}
