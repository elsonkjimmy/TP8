<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'System',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        // Teachers (10 teachers)
        $teachers = [
            ['first' => 'Jean', 'last' => 'Dupont'],
            ['first' => 'Marie', 'last' => 'Curie'],
            ['first' => 'Albert', 'last' => 'Einstein'],
            ['first' => 'Isaace', 'last' => 'Newton'],
            ['first' => 'Alan', 'last' => 'Turing'],
            ['first' => 'Grace', 'last' => 'Hopper'],
            ['first' => 'Ada', 'last' => 'Lovelace'],
            ['first' => 'Blaise', 'last' => 'Pascal'],
            ['first' => 'Charles', 'last' => 'Babbage'],
            ['first' => 'Tim', 'last' => 'Berners-Lee'],
        ];

        foreach ($teachers as $teacher) {
            User::firstOrCreate(
                ['email' => strtolower($teacher['first']) . '.' . strtolower($teacher['last']) . '@school.com'],
                [
                    'first_name' => $teacher['first'],
                    'last_name' => $teacher['last'],
                    'role' => 'teacher',
                    'password' => Hash::make('password'),
                ]
            );
        }

        // Delegates will be created in GrouperSeeder/FiliereSeeder or here?
        // Let's create some delegates here for now, they can be assigned later
        $delegates = [
            ['first' => 'Paul', 'last' => 'Student'],
            ['first' => 'Jacques', 'last' => 'Eleve'],
            ['first' => 'Pierre', 'last' => 'Apprenant'],
            ['first' => 'Sophie', 'last' => 'Etudiante'],
            ['first' => 'Julie', 'last' => 'Scolaire'],
        ];

        foreach ($delegates as $delegate) {
            User::firstOrCreate(
                ['email' => strtolower($delegate['first']) . '.' . strtolower($delegate['last']) . '@student.com'],
                [
                    'first_name' => $delegate['first'],
                    'last_name' => $delegate['last'],
                    'role' => 'delegate',
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}
