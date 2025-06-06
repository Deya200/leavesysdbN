<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create the users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Employee full name
            $table->string('EmployeeNumber')->unique(); // Unique employee number
            $table->string('password'); // Password for login
            $table->string('email')->unique(); // Ensure email is included
            $table->unsignedInteger('remaining_leave_days')->default(30); // Default leave days
            $table->timestamps();
            $table->rememberToken();
            $table->string('profile_photo')->nullable();

        });

        // Add 'gender' column to the users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('email'); // Add the 'gender' column
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
