<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('OvertimeHoursWeekdays', 8, 2)->default(0)->after('OtherAllowance');
            $table->decimal('OvertimeHoursWeekend', 8, 2)->default(0)->after('OvertimeHoursWeekdays');
            $table->decimal('Bonus', 12, 2)->default(0)->after('OvertimeHoursWeekend');
            $table->decimal('TevetLevy', 12, 2)->default(0)->after('EmployerPension');
            $table->decimal('LoanAdvanceDeduction', 12, 2)->default(0)->after('TevetLevy');
            $table->decimal('AdminFees', 12, 2)->default(0)->after('LoanAdvanceDeduction');
            $table->decimal('TotalContribution', 12, 2)->default(0)->after('AdminFees');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'OvertimeHoursWeekdays',
                'OvertimeHoursWeekend',
                'Bonus',
                'TevetLevy',
                'LoanAdvanceDeduction',
                'AdminFees',
                'TotalContribution',
            ]);
        });
    }
};
