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
        Schema::create('locum_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('DepartmentID');
            $table->string('position_type'); // e.g., 'Nurse', 'Doctor', 'Specialist'
            $table->decimal('daily_rate', 8, 2); // Rate per day
            $table->decimal('hourly_rate', 8, 2)->nullable(); // Optional hourly rate
            $table->string('currency', 3)->default('USD');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('DepartmentID')->references('DepartmentID')->on('departments')->onDelete('cascade');
            $table->unique(['DepartmentID', 'position_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locum_rates');
    }
};
