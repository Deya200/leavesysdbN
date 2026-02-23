<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('EmployeeNumber');
            $table->string('action');
            $table->string('table_name');
            $table->bigInteger('record_id');
            $table->timestamp('timestamp')->default(now());
            $table->timestamps();

            $table->foreign('EmployeeNumber')
                  ->references('EmployeeNumber')
                  ->on('employees')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
