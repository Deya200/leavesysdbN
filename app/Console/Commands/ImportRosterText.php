<?php

namespace App\Console\Commands;

use App\Models\Department;
use App\Models\Employee;
use App\Models\RosterAttendance;
use App\Models\ShiftAssignment;
use App\Models\Ward;
use Illuminate\Console\Command;

class ImportRosterText extends Command
{
    protected $signature = 'roster:import-text {path} {month} {year}';

    protected $description = 'Parse a plain-text duty roster and import for a given month/year';

    public function handle()
    {
        $path = $this->argument('path');
        $month = (int) $this->argument('month');
        $year = (int) $this->argument('year');

        if (!file_exists($path)) {
            $this->error("File not found: {$path}");
            return 1;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $dayHeaderIndex = null;
        foreach ($lines as $i => $line) {
            if (preg_match('/\b1\b\s+2\b/', $line) || preg_match('/\b1\b\s+2\b\s+3\b/', $line)) {
                $dayHeaderIndex = $i;
                break;
            }
            if (preg_match('/^\s*1\s+2\s+3\s+4\s+5\s+/',$line)) {
                $dayHeaderIndex = $i;
                break;
            }
        }

        if ($dayHeaderIndex === null) {
            $this->error('Could not find day header line in text file.');
            return 1;
        }

        // Process lines after header
        $imported = 0;
        $currentWard = null;

        for ($i = $dayHeaderIndex + 1; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            // If line is an uppercase short word(s) with no codes, treat as ward header
            if (preg_match('/^[A-Z\s\/]+$/', $line) && substr_count($line, ' ') < 6) {
                $currentWard = trim($line);
                continue;
            }

            // Tokenize by whitespace and find first code token index
            $tokens = preg_split('/\s+/', $line);
            if (count($tokens) < 3) continue;

            $knownCodes = ['ST','DO','PH','N','SD','H','CALL','3PH'];
            $firstCodeIndex = null;
            foreach ($tokens as $ti => $tkn) {
                if (in_array(strtoupper($tkn), $knownCodes, true)) {
                    $firstCodeIndex = $ti;
                    break;
                }
            }

            if ($firstCodeIndex === null) {
                continue; // no codes found on this line
            }

            // Name is tokens before cadre (cadre is token just before first code)
            $cadreIndex = max(0, $firstCodeIndex - 1);
            $nameTokens = array_slice($tokens, 0, max(1, $cadreIndex));
            $name = trim(implode(' ', $nameTokens));
            $codes = array_slice($tokens, $firstCodeIndex);

            // If codes length < 28, still attempt but only map what we have
            foreach ($codes as $dayIndex => $codeRaw) {
                $code = strtoupper(trim($codeRaw));
                if ($code === '') continue;
                $day = $dayIndex + 1; // assume first code maps to day 1
                if ($day > cal_days_in_month(CAL_GREGORIAN, $month, $year)) continue;
                $date = sprintf('%04d-%02d-%02d', $year, $month, $day);

                // Find employee by last name token (last word)
                $parts = preg_split('/\s+/', $name);
                $last = end($parts);
                $employee = Employee::where('LastName', 'ilike', $last)->first();
                if (! $employee) {
                    $employee = Employee::where('FirstName', 'ilike', $parts[0] ?? $name)->first();
                }
                if (! $employee) {
                    // create a temp employee in Clinical dept to allow roster import
                    $dept = Department::firstOrCreate(['DepartmentName' => 'Clinical']);
                    $grade = \App\Models\Grade::first();
                    $position = \App\Models\Position::first();
                    $employee = Employee::create([
                        'EmployeeNumber' => 'TMP-'.strtoupper(substr(md5($name.$i.$day),0,8)),
                        'FirstName' => $parts[0] ?? $name,
                        'LastName' => $last,
                        'Gender' => 'Male',
                        'DateOfBirth' => now()->subYears(30)->format('Y-m-d'),
                        'DepartmentID' => $dept->DepartmentID,
                        'GradeID' => $grade?->GradeID,
                        'PositionID' => $position?->PositionID,
                        'SupervisorID' => null,
                        'password' => bcrypt('password123'),
                        'role_id' => 3,
                        'RemainingAnnualLeaveDays' => 0,
                    ]);
                    $this->info("Created temp employee {$employee->EmployeeNumber} for name '{$name}'");
                }

                // Map codes to shift/status
                $map = [
                    'ST' => ['shift' => 'day', 'status' => 'present'],
                    'DO' => ['shift' => 'day', 'status' => 'absent'],
                    'PH' => ['shift' => 'day', 'status' => 'on_holiday'],
                    'N'  => ['shift' => 'night', 'status' => 'present'],
                    'SD' => ['shift' => 'day', 'status' => 'present'],
                    'H'  => ['shift' => 'day', 'status' => 'on_holiday'],
                    'CALL' => ['shift' => 'day', 'status' => 'present', 'note' => 'on call'],
                ];

                $info = $map[$code] ?? null;
                if (! $info) {
                    $this->warn("Unrecognized code '{$code}' for {$name} on {$date}");
                    continue;
                }

                // Find or create ward
                $wardId = null;
                if ($currentWard) {
                    $ward = Ward::firstOrCreate(['WardName' => $currentWard], ['DepartmentID' => $employee->DepartmentID ?? null]);
                    $wardId = $ward->WardID;
                }

                ShiftAssignment::updateOrCreate([
                    'EmployeeNumber' => $employee->EmployeeNumber,
                    'AssignmentDate' => $date,
                    'Shift' => $info['shift'],
                ], [
                    'WardID' => $wardId,
                    'Notes' => $info['note'] ?? null,
                ]);

                RosterAttendance::updateOrCreate([
                    'EmployeeNumber' => $employee->EmployeeNumber,
                    'AttendanceDate' => $date,
                    'Shift' => $info['shift'],
                ], [
                    'Status' => $info['status'],
                    'Notes' => $info['note'] ?? null,
                ]);

                $imported++;
            }
        }

        $this->info("Imported {$imported} entries from text roster.");
        return 0;
    }
}
