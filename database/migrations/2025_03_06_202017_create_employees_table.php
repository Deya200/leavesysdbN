<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Employee;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->string('EmployeeNumber')->primary(); // Primary key for the employees table
            $table->string('FirstName', 100); // Employee's first name
            $table->string('LastName', 100); // Employee's last name
            $table->enum('Gender', ['Male', 'Female', 'Other']); // Gender selection
            $table->date('DateOfBirth'); // Date of birth
            $table->unsignedBigInteger('DepartmentID'); // Foreign key referencing departments
            $table->unsignedBigInteger('GradeID'); // Foreign key referencing grades
            $table->unsignedBigInteger('PositionID'); // Foreign key referencing positions
            $table->string('SupervisorID')->nullable(); // Self-referencing foreign key using Employee Number
            $table->string('email')->unique()->nullable(); // Email for authentication
            $table->string('password')->nullable(); // Password for authentication
            $table->integer('role_id')->default(2); // Role ID (default: Employee)
            $table->rememberToken(); // For remember me functionality
            
            // Define foreign key constraints
            $table->foreign('DepartmentID')->references('DepartmentID')->on('departments')->onDelete('cascade');
            $table->foreign('GradeID')->references('GradeID')->on('grades')->onDelete('cascade');
            $table->foreign('PositionID')->references('PositionID')->on('positions')->onDelete('cascade');
            // Self-referencing foreign key will be added in a later migration after table is fully created
            
            // Add timestamps for created_at and updated_at
            $table->timestamps();
        });
        
        // Add self-referencing foreign key after table creation
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('SupervisorID')->references('EmployeeNumber')->on('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees'); // Rollback - drops the employees table
    }
};
