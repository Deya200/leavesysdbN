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
    Schema::create('employee_leave_balance', function (Blueprint $table) {
        $table->unsignedBigInteger('EmployeeNumber')->primary(); // Primary Key and Foreign Key
        $table->integer('AnnualLeaveBalance')->unsigned(); // Leave balance cannot be negative
        $table->foreign('EmployeeNumber')->references('EmployeeNumber')->on('employees')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_leave_balance');
    }
};
