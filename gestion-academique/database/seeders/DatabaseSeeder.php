<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            FiliereSeeder::class,
            SalleSeeder::class,
            GroupeSeeder::class,
            UESeeder::class,
            DesiderataSeeder::class, // Previous one exists
        ]);
    }
}
