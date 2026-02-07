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
            $table->boolean('can_be_appealed')->default(true)->after('AdminRejectionReason');
            $table->timestamp('appeal_deadline')->nullable()->after('can_be_appealed');
            $table->boolean('is_active')->default(false)->after('appeal_deadline');
            $table->boolean('is_cancelled')->default(false)->after('is_active');
            $table->integer('carried_over_days')->default(0)->after('is_cancelled');
            $table->integer('financial_year')->nullable()->after('carried_over_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn([
                'can_be_appealed',
                'appeal_deadline',
                'is_active',
                'is_cancelled',
                'carried_over_days',
                'financial_year'
            ]);
        });
    }
};
