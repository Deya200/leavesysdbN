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
    Schema::create('leave_requests', function (Blueprint $table) {
        $table->id('LeaveRequestID'); // Primary Key
        $table->unsignedBigInteger('EmployeeNumber'); // Foreign Key to Employees
        $table->unsignedBigInteger('LeaveTypeID'); // Foreign Key to LeaveTypes
        $table->date('StartDate');
        $table->date('EndDate');
        $table->integer('TotalDays')->unsigned(); // Days cannot be negative
        $table->boolean('SupervisorApproval')->default(false);
        $table->boolean('HRApproval')->default(false);
        $table->string('RequestStatus', 50)->default('Pending'); // Adding max length and default value
        $table->foreign('EmployeeNumber')->references('EmployeeNumber')->on('employees')->onDelete('cascade');
        $table->foreign('LeaveTypeID')->references('LeaveTypeID')->on('leave_types')->onDelete('cascade');
        $table->timestamps();
        $table->unsignedBigInteger('SupervisorID')->nullable(false)->change();
        
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
