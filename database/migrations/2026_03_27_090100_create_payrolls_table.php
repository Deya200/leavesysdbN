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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id('PayrollID');
            $table->string('EmployeeNumber');
            $table->date('PeriodStart');
            $table->date('PeriodEnd');
            $table->decimal('BasicPay', 12, 2)->default(0);
            $table->decimal('OvertimePay', 12, 2)->default(0);
            $table->decimal('Deductions', 12, 2)->default(0);
            $table->decimal('NetPay', 12, 2)->default(0);
            $table->enum('Status', ['Draft', 'Processed', 'Paid'])->default('Draft');
            $table->string('ProcessedBy')->nullable();
            $table->timestamp('ProcessedAt')->nullable();
            $table->timestamps();

            $table->foreign('EmployeeNumber')->references('EmployeeNumber')->on('employees')->onDelete('cascade');
            $table->foreign('ProcessedBy')->references('EmployeeNumber')->on('employees')->nullOnDelete();
            $table->unique(['EmployeeNumber', 'PeriodStart', 'PeriodEnd']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
