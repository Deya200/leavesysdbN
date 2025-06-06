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
    Schema::create('grades', function (Blueprint $table) {
        $table->id('GradeID'); // Primary Key
        $table->string('GradeName', 100);
        $table->integer('AnnualLeaveDays')->unsigned(); // Unsigned as leave days cannot be negative
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
