<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     * Change user_id from bigint to string to support string primary keys (e.g. "WF-EMP-001").
     */
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Drop the old index if it exists
            $table->dropIndex(['user_id']);
        });

        // Use raw SQL to change the column type since PostgreSQL requires it
        DB::statement('ALTER TABLE sessions ALTER COLUMN user_id TYPE VARCHAR(255)');

        Schema::table('sessions', function (Blueprint $table) {
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        DB::statement('ALTER TABLE sessions ALTER COLUMN user_id TYPE BIGINT USING user_id::BIGINT');

        Schema::table('sessions', function (Blueprint $table) {
            $table->index('user_id');
        });
    }
};
