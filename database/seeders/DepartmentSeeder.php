<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            [
                'DepartmentName' => 'IT',
                'Description' => 'Handles all IT and system operations',
                'SupervisorID' => null,
                'HeadOfDepartmentID' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'DepartmentName' => 'HR',
                'Description' => 'Manages employee relations and recruitment',
                'SupervisorID' => null,
                'HeadOfDepartmentID' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'DepartmentName' => 'Finance',
                'Description' => 'Responsible for budgeting and financial planning',
                'SupervisorID' => null,
                'HeadOfDepartmentID' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'DepartmentName' => 'Operations',
                'Description' => 'Oversees daily operations and logistics',
                'SupervisorID' => null,
                'HeadOfDepartmentID' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
