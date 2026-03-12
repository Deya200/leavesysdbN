<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positions')->insert([
            [
                'PositionName' => 'System Administrator',
                'GradeID' => 1, // adjust to match existing grades
                'DepartmentID' => 1, // Administration
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'PositionName' => 'HR Manager',
                'GradeID' => 2,
                'DepartmentID' => 2, // Customer Care / HR
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'PositionName' => 'Financial Analyst',
                'GradeID' => 3,
                'DepartmentID' => 9, // Admin and Finance
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'PositionName' => 'Clinical Officer',
                'GradeID' => 4,
                'DepartmentID' => 3, // Clinical
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'PositionName' => 'Lab Technician',
                'GradeID' => 5,
                'DepartmentID' => 4, // Laboratory
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'PositionName' => 'Pharmacist',
                'GradeID' => 6,
                'DepartmentID' => 5, // Pharmacy
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'PositionName' => 'Radiologist',
                'GradeID' => 7,
                'DepartmentID' => 6, // Radiology
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'PositionName' => 'Nurse',
                'GradeID' => 8,
                'DepartmentID' => 7, // Nursing
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
