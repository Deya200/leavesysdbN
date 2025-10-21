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
        Schema::table('leave_types', function (Blueprint $table) {
            // ✅ Change default value of DeductsFromAnnual to false (0)
            $table->boolean('DeductsFromAnnual')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            // 🔁 Revert default back to true (1) if needed
            $table->boolean('DeductsFromAnnual')->default(true)->change();
        });
    }
};
