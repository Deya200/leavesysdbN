<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('HomeAddress')->nullable()->after('LastName');
            $table->string('ResidentialAddress')->nullable()->after('HomeAddress');
            $table->string('NextOfKin')->nullable()->after('ResidentialAddress');
            $table->date('AppointmentDate')->nullable()->after('DateOfBirth');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'HomeAddress',
                'ResidentialAddress',
                'NextOfKin',
                'AppointmentDate',
            ]);
        });
    }
};
