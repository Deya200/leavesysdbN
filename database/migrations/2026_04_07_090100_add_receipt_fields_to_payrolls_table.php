<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('HousingAllowance', 12, 2)->default(0)->after('OvertimePay');
            $table->decimal('TransportAllowance', 12, 2)->default(0)->after('HousingAllowance');
            $table->decimal('MedicalAllowance', 12, 2)->default(0)->after('TransportAllowance');
            $table->decimal('OtherAllowance', 12, 2)->default(0)->after('MedicalAllowance');
            $table->decimal('GrossPay', 12, 2)->default(0)->after('OtherAllowance');
            $table->decimal('PAYE', 12, 2)->default(0)->after('GrossPay');
            $table->decimal('EmployeePension', 12, 2)->default(0)->after('PAYE');
            $table->decimal('EmployerPension', 12, 2)->default(0)->after('EmployeePension');
            $table->decimal('OtherDeductions', 12, 2)->default(0)->after('EmployerPension');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'HousingAllowance',
                'TransportAllowance',
                'MedicalAllowance',
                'OtherAllowance',
                'GrossPay',
                'PAYE',
                'EmployeePension',
                'EmployerPension',
                'OtherDeductions',
            ]);
        });
    }
};
