<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id('DepartmentID'); // Primary Key
            $table->string('DepartmentName', 150); // Department Name (Max length of 150)
            
            // Foreign key to Supervisor (Employee table)
            $table->unsignedBigInteger('SupervisorID')->nullable(); 
            $table->foreign('SupervisorID')
                  ->references('EmployeeNumber')
                  ->on('employees')
                  ->onDelete('set null');

            // Foreign key to Head of Department (Employee table)
            $table->unsignedBigInteger('HeadOfDepartmentID')->nullable(); 
            $table->foreign('HeadOfDepartmentID')
                  ->references('EmployeeNumber')
                  ->on('employees')
                  ->onDelete('set null');
            
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            // Drop foreign keys first to avoid errors
            $table->dropForeign(['SupervisorID']);
            $table->dropForeign(['HeadOfDepartmentID']);
        });

        Schema::dropIfExists('departments'); // Drop table
    }
};
