<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix PostgreSQL sequence for auto-incrementing LeaveTypeID
        // This ensures the next insert will use the correct ID
        DB::statement(
            "SELECT setval(pg_get_serial_sequence('leave_types', 'LeaveTypeID'), 
            COALESCE((SELECT MAX(\"LeaveTypeID\") FROM \"leave_types\")+1, 1));"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed for reversal - this is a data fix
    }
};
