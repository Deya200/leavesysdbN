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
            $table->text('SupervisorApprovalNote')->nullable();
            $table->text('AdminApprovalNote')->nullable();
            // Re-adding these just in case they were missing from some earlier migration,
            // though the model suggests they exist. Let's make them nullable if they already exist
            // but for safety in a migration, it's better to check or just add them.
            // Actually, I'll just add the Notes for now.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn(['SupervisorApprovalNote', 'AdminApprovalNote']);
        });
    }
};
