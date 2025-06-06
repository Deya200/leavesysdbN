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
        Schema::create('positions', function (Blueprint $table) {
            $table->id('PositionID'); // Primary Key
            $table->string('PositionName', 150); // Adding max length for consistency
            $table->unsignedBigInteger('GradeID'); // Foreign Key to Grades
            $table->foreign('GradeID')->references('GradeID')->on('grades')->onDelete('cascade');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
