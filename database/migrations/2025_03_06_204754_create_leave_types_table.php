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
    public function up()
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id('LeaveTypeID'); // Primary key
            $table->string('LeaveTypeName', 150); // String for leave type name
            $table->boolean('IsPaidLeave'); // Boolean for paid leave status
            $table->enum('GenderApplicable', ['Male', 'Female', 'Both']); // Enum for gender
            $table->timestamps(); // created_at and updated_at
        });
        
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
