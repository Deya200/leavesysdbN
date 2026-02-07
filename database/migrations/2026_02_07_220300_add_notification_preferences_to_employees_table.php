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
        Schema::table('employees', function (Blueprint $table) {
            $table->boolean('email_notifications_enabled')->default(true)->after('RemainingAnnualLeaveDays');
            $table->boolean('system_notifications_enabled')->default(true)->after('email_notifications_enabled');
            $table->integer('carried_over_leave_days')->default(0)->after('system_notifications_enabled');
            $table->timestamp('last_password_reset_at')->nullable()->after('carried_over_leave_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'email_notifications_enabled',
                'system_notifications_enabled',
                'carried_over_leave_days',
                'last_password_reset_at'
            ]);
        });
    }
};
