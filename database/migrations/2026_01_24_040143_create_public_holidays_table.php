<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('public_holidays', function (Blueprint $table) {
            // add date and name if they don't already exist
            if (!Schema::hasColumn('public_holidays', 'date')) {
                $table->date('date')->unique()->after('id');
            }
            if (!Schema::hasColumn('public_holidays', 'name')) {
                $table->string('name')->nullable()->after('date');
            }
        });
    }

    public function down()
    {
        Schema::table('public_holidays', function (Blueprint $table) {
            if (Schema::hasColumn('public_holidays', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('public_holidays', 'date')) {
                $table->dropColumn('date');
            }
        });
    }
};
