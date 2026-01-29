<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Filiere;
use App\Models\Groupe;
use App\Models\Ue;
use App\Models\Salle;
use App\Models\SeanceTemplate;

class DesiderataSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $teacher = User::firstOrCreate(
            ['email' => 'teacher@test.com'],
            [
                'first_name' => 'John',
                'last_name' => 'Teacher',
                'role' => 'teacher',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Reference Data
        $filiere = Filiere::firstOrCreate(
            ['code' => 'INFO'],
            ['nom' => 'Informatique', 'enseignant_responsable_id' => $teacher->id]
        );

        $groupe = Groupe::firstOrCreate(
            ['nom' => 'L3 Info'],
            ['filiere_id' => $filiere->id]
        );

        $ue = Ue::firstOrCreate(
            ['code' => 'INF301'],
            [
                'nom' => 'Programmation Web',
                'filiere_id' => $filiere->id,
                'enseignant_id' => $teacher->id,
            ]
        );

        $salle = Salle::firstOrCreate(
            ['numero' => 'C104'],
            ['capacite' => 100]
        );

        // 3. Create Unassigned Seance Templates (Slots)
        // Slot 1: Monday 08:00 - 11:00
        SeanceTemplate::create([
            'filiere_id' => $filiere->id,
            'groupe_id' => $groupe->id,
            'ue_id' => $ue->id,
            'salle_id' => $salle->id,
            'enseignant_id' => null, // UNASSIGNED - This is what allows the teacher to request it
            'day_of_week' => 1, // Lundi
            'start_time' => '08:00:00',
            'end_time' => '11:00:00',
        ]);

        // Slot 2: Wednesday 14:00 - 17:00
        SeanceTemplate::create([
            'filiere_id' => $filiere->id,
            'groupe_id' => $groupe->id,
            'ue_id' => $ue->id,
            'salle_id' => $salle->id,
            'enseignant_id' => null, // UNASSIGNED
            'day_of_week' => 3, // Mercredi
            'start_time' => '14:00:00',
            'end_time' => '17:00:00',
        ]);

        $this->command->info('Desiderata verification data seeded successfully!');
        $this->command->info('Admin: admin@test.com / password');
        $this->command->info('Teacher: teacher@test.com / password');
    }
}
