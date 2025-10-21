<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id('LeaveTypeID'); // Primary key
            $table->string('LeaveTypeName', 150); // Name of the leave type
            $table->boolean('IsPaidLeave'); // Whether the leave is paid
            $table->enum('GenderApplicable', ['Male', 'Female', 'Both']); // Gender applicability
            $table->boolean('DeductsFromAnnual')->default(false); // ✅ New field: deducts from annual leave
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
