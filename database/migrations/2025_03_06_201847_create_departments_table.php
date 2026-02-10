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
            $table->text('Description')->nullable(); // Department Description
            
            // These will be added as foreign keys in a later migration (after employees table exists)
            $table->string('SupervisorID')->nullable(); // Alphanumeric Employee Number
            $table->string('HeadOfDepartmentID')->nullable(); // Alphanumeric Employee Number            
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments'); // Drop table
    }
};
