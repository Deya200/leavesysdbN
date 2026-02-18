<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetPgSequences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset-sequences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset PostgreSQL sequences for all auto-incrementing primary keys';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tables = [
            ['leave_types', 'LeaveTypeID'],
            ['roles', 'id'],
            ['grades', 'GradeID'],
            ['departments', 'DepartmentID'],
            ['positions', 'PositionID'],
            ['employees', 'EmployeeNumber', false], // EmployeeNumber is a string, no sequence
        ];

        foreach ($tables as $table) {
            $tableName = $table[0];
            $columnName = $table[1];
            $isSerial = $table[2] ?? true;

            if (!$isSerial)
                continue;

            $this->info("Resetting sequence for {$tableName}.{$columnName}...");

            try {
                // Get the sequence name
                $sequence = DB::selectOne("SELECT pg_get_serial_sequence(?, ?)", [$tableName, $columnName]);

                if ($sequence && $seqName = $sequence->pg_get_serial_sequence) {
                    $maxId = DB::table($tableName)->max($columnName) ?? 0;
                    DB::statement("SELECT setval(?, ?, true)", [$seqName, $maxId]);
                    $this->comment("  -> Sequence {$seqName} reset to {$maxId}");
                }
                else {
                    $this->warn("  -> No sequence found for {$tableName}.{$columnName}");
                }
            }
            catch (\Exception $e) {
                $this->error("  -> Error: " . $e->getMessage());
            }
        }

        $this->info('Sequence reset complete!');
    }
}
