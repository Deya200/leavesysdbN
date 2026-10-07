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
            $table->string('activation_token')->nullable()->unique()->after('is_locum');
            $table->timestamp('activated_at')->nullable()->after('activation_token');
            $table->text('locum_notes')->nullable()->after('activated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['activation_token', 'activated_at', 'locum_notes']);
        });
    }
};
