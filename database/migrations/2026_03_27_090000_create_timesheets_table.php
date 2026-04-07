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
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id('TimesheetID');
            $table->string('EmployeeNumber');
            $table->date('WorkDate');
            $table->decimal('HoursWorked', 5, 2);
            $table->decimal('OvertimeHours', 5, 2)->default(0);
            $table->text('Notes')->nullable();
            $table->enum('Status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->string('ApprovedBy')->nullable();
            $table->timestamp('ApprovedAt')->nullable();
            $table->timestamps();

            $table->foreign('EmployeeNumber')->references('EmployeeNumber')->on('employees')->onDelete('cascade');
            $table->foreign('ApprovedBy')->references('EmployeeNumber')->on('employees')->nullOnDelete();
            $table->unique(['EmployeeNumber', 'WorkDate']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};
