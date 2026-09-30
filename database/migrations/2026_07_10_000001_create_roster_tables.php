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
        // Wards table
        Schema::create('wards', function (Blueprint $table) {
            $table->id('WardID');
            $table->string('WardName', 150)->unique();
            $table->text('Description')->nullable();
            $table->unsignedBigInteger('DepartmentID');
            $table->timestamps();

            $table->foreign('DepartmentID')->references('DepartmentID')->on('departments')->onDelete('cascade');
        });

        // Shift assignments for employees
        Schema::create('shift_assignments', function (Blueprint $table) {
            $table->id('ShiftAssignmentID');
            $table->string('EmployeeNumber');
            $table->enum('Shift', ['day', 'night'])->default('day');
            $table->date('AssignmentDate');
            $table->unsignedBigInteger('WardID')->nullable();
            $table->text('Notes')->nullable();
            $table->timestamps();

            $table->foreign('EmployeeNumber')->references('EmployeeNumber')->on('employees')->onDelete('cascade');
            $table->foreign('WardID')->references('WardID')->on('wards')->onDelete('set null');
            $table->unique(['EmployeeNumber', 'AssignmentDate', 'Shift']);
        });

        // Attendance tracking (devotion, sick, holiday, on-duty)
        Schema::create('roster_attendance', function (Blueprint $table) {
            $table->id('AttendanceID');
            $table->string('EmployeeNumber');
            $table->date('AttendanceDate');
            $table->enum('Shift', ['day', 'night'])->default('day');
            $table->enum('Status', ['present', 'sick', 'on_holiday', 'absent'])->default('present');
            $table->boolean('MorningDevotionAttended')->default(false);
            $table->text('Notes')->nullable();
            $table->timestamps();

            $table->foreign('EmployeeNumber')->references('EmployeeNumber')->on('employees')->onDelete('cascade');
            $table->unique(['EmployeeNumber', 'AttendanceDate', 'Shift']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roster_attendance');
        Schema::dropIfExists('shift_assignments');
        Schema::dropIfExists('wards');
    }
};
