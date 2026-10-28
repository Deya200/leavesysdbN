<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('staff_voice_comments')) {
            return;
        }

        Schema::create('staff_voice_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_voice_entry_id')->constrained('staff_voice_entries')->cascadeOnDelete();
            $table->string('employee_number');
            $table->string('author_name');
            $table->text('body');
            $table->timestamps();
            $table->foreign('employee_number')->references('EmployeeNumber')->on('employees')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_voice_comments');
    }
};
