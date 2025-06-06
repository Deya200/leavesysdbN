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
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->boolean('AdminVerified')->default(false); // Tracks admin's verification status
            $table->text('AdminNotes')->nullable();          // Optional notes added by the admin
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn('AdminVerified'); // Removes admin verification column
            $table->dropColumn('AdminNotes');    // Removes admin notes column
        });
    }
};
