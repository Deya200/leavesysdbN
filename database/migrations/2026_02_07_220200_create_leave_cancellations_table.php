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
        Schema::create('leave_cancellations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leave_request_id');
            $table->string('employee_number');
            $table->text('cancellation_reason');
            $table->string('status', 50)->default('Pending'); // Pending, Approved, Rejected
            $table->integer('cancelled_days')->unsigned()->default(0);
            $table->integer('refunded_days')->unsigned()->default(0);
            $table->boolean('approved')->default(false);
            $table->string('reviewer_id')->nullable();
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('leave_cancellations');
    }
};
