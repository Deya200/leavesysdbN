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
        Schema::create('leave_extensions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leave_request_id');
            $table->string('employee_number');
            $table->date('original_end_date');
            $table->date('requested_end_date');
            $table->integer('extension_days')->unsigned();
            $table->text('reason');
            $table->string('status', 50)->default('Pending'); // Pending, Approved, Rejected
            $table->boolean('supervisor_approval')->default(false);
            $table->boolean('admin_approval')->default(false);
            $table->string('reviewer_id')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('leave_request_id')->references('LeaveRequestID')->on('leave_requests')->onDelete('cascade');
            $table->foreign('employee_number')->references('EmployeeNumber')->on('employees')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('EmployeeNumber')->on('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_extensions');
    }
};
