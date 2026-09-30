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
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('notes');
            $table->string('approved_by')->nullable()->after('approval_status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->text('supervisor_notes')->nullable()->after('rejected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locum_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'approval_status',
                'approved_by',
                'approved_at',
                'rejected_at',
                'supervisor_notes',
            ]);
        });
    }
};
