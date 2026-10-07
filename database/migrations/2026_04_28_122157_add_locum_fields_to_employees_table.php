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
            $table->boolean('is_locum')->default(false)->after('profile_photo');
            $table->enum('employment_type', ['Permanent', 'Temporary', 'Locum', 'Contract'])->default('Permanent')->after('is_locum');
            $table->date('contract_start_date')->nullable()->after('employment_type');
            $table->date('contract_end_date')->nullable()->after('contract_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['is_locum', 'employment_type', 'contract_start_date', 'contract_end_date']);
        });
    }
};
