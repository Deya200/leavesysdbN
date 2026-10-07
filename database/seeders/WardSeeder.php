<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clinical Department ID = 3
        $clinicalDepartmentId = 3;

        DB::table('wards')->insert([
            [
                'WardName' => 'General Ward',
                'Description' => 'General medical ward for non-critical patients',
                'DepartmentID' => $clinicalDepartmentId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'WardName' => 'ICU',
                'Description' => 'Intensive Care Unit for critical patients',
                'DepartmentID' => $clinicalDepartmentId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'WardName' => 'Pediatrics',
                'Description' => 'Ward for pediatric patients',
                'DepartmentID' => $clinicalDepartmentId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'WardName' => 'Surgery',
                'Description' => 'Surgical ward for pre and post-operative care',
                'DepartmentID' => $clinicalDepartmentId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'WardName' => 'Maternity',
                'Description' => 'Maternity and obstetrics ward',
                'DepartmentID' => $clinicalDepartmentId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'WardName' => 'Emergency',
                'Description' => 'Emergency and trauma ward',
                'DepartmentID' => $clinicalDepartmentId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
