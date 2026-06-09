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
        Schema::create('locum_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('EmployeeNumber');
            $table->unsignedBigInteger('DepartmentID');
            $table->timestamp('sign_in_time');
            $table->timestamp('sign_out_time')->nullable();
            $table->decimal('hours_worked', 5, 2)->nullable();
            $table->date('session_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('EmployeeNumber')->references('EmployeeNumber')->on('employees')->onDelete('cascade');
            $table->foreign('DepartmentID')->references('DepartmentID')->on('departments')->onDelete('cascade');
            $table->index(['EmployeeNumber', 'session_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locum_sessions');
    }
};
