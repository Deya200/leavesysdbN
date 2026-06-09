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
        Schema::table('locum_rates', function (Blueprint $table) {
            // Drop existing unique constraint
            $table->dropUnique(['DepartmentID', 'position_type']);
            
            // Add shift column with enum
            $table->enum('shift', ['day', 'night'])->default('day')->after('position_type');
            
            // Add new unique constraint including shift
            $table->unique(['DepartmentID', 'position_type', 'shift'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locum_rates', function (Blueprint $table) {
            $table->dropUnique(['DepartmentID', 'position_type', 'shift']);
            $table->dropColumn('shift');
            $table->unique(['DepartmentID', 'position_type']);
        });
    }
};
