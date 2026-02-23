<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('training')->insert([
            [
                'name' => 'Cybersecurity Awareness',
                'description' => 'Training on safe IT practices and phishing prevention.',
                'date' => '2026-02-15',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Leave Policy Orientation',
                'description' => 'Overview of leave request, cancellation, and extension procedures.',
                'date' => '2026-02-20',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Data Protection Workshop',
                'description' => 'Hands-on training on GDPR and HIPAA compliance.',
                'date' => '2026-03-01',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
