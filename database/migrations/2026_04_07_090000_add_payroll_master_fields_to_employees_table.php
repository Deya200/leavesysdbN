<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('DutyStation')->nullable()->after('PositionID');
            $table->string('BankName')->nullable()->after('DutyStation');
            $table->string('BankBranch')->nullable()->after('BankName');
            $table->string('BankAccountNumber')->nullable()->after('BankBranch');
            $table->string('PensionNumber')->nullable()->after('BankAccountNumber');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'DutyStation',
                'BankName',
                'BankBranch',
                'BankAccountNumber',
                'PensionNumber',
            ]);
        });
    }
};
