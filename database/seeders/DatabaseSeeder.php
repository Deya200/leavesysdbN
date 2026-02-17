<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the production data seeder
        $this->call([
            ProductionDataSeeder::class ,
            WorkflowTestSeeder::class ,
        ]);
    }
}
