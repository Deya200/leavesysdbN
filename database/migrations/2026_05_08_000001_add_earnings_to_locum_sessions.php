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
        Schema::table('locum_sessions', function (Blueprint $table) {
            $table->decimal('hourly_rate', 10, 2)->default(2000)->after('hours_worked')->comment('Hourly rate in currency (default 2000)');
            $table->decimal('total_earnings', 10, 2)->nullable()->after('hourly_rate')->comment('Total earnings for session');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locum_sessions', function (Blueprint $table) {
            $table->dropColumn('hourly_rate');
            $table->dropColumn('total_earnings');
        });
    }
};
