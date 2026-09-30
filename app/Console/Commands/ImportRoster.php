<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\RosterAttendance;
use App\Models\ShiftAssignment;
use App\Models\Ward;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportRoster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roster:import {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import duty roster from CSV into shift_assignments and roster_attendance';

    public function handle()
    {
        $path = $this->argument('path');

        if (!file_exists($path)) {
            $this->error("File not found: {$path}");
            return 1;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $mapping = [
            'ST' => ['Shift' => 'day', 'Status' => 'present'],
            'DO' => ['Shift' => 'day', 'Status' => 'absent'],
            'PH' => ['Shift' => 'day', 'Status' => 'on_holiday'],
            'N'  => ['Shift' => 'night', 'Status' => 'present'],
            'SD' => ['Shift' => 'day', 'Status' => 'present'],
            'H'  => ['Shift' => 'day', 'Status' => 'on_holiday'],
            'CALL' => ['Shift' => 'day', 'Status' => 'present', 'Note' => 'on call'],
            '3PH' => ['Shift' => 'day', 'Status' => 'on_holiday'],
        ];

        $count = 0;
        foreach ($records as $row) {
            $date = $row['Date'] ?? null;
            $code = strtoupper(trim($row['Code'] ?? ''));
            $empNumber = trim($row['EmployeeNumber'] ?? '');
            $first = trim($row['FirstName'] ?? '');
            $last = trim($row['LastName'] ?? '');
            $wardName = trim($row['WardName'] ?? '');

            if (! $date || ! $code) {
                continue;
            }

            $employee = null;
            if ($empNumber) {
                $employee = Employee::where('EmployeeNumber', $empNumber)->first();
            }
            if (! $employee && $last) {
                $employee = Employee::where('LastName', 'ilike', $last)->first();
            }
            if (! $employee && $first) {
                $employee = Employee::where('FirstName', 'ilike', $first)->first();
            }

            if (! $employee) {
                $this->warn("Employee not found for row: {$first} {$last} ({$empNumber})");
                continue;
            }

            $wardId = null;
            if ($wardName) {
                $ward = Ward::where('WardName', $wardName)->first();
                if ($ward) $wardId = $ward->WardID;
            }

            $info = $mapping[$code] ?? null;
            if (! $info) {
                $this->warn("Unknown code '{$code}' for {$employee->EmployeeNumber} on {$date}");
                continue;
            }

            $shift = $info['Shift'];
            $status = $info['Status'];
            $notes = $info['Note'] ?? ($row['Notes'] ?? null);

            // Shift assignment
            ShiftAssignment::updateOrCreate(
                [
                    'EmployeeNumber' => $employee->EmployeeNumber,
                    'AssignmentDate' => $date,
                    'Shift' => $shift,
                ],
                [
                    'WardID' => $wardId,
                    'Notes' => $notes,
                ]
            );

            // Attendance
            RosterAttendance::updateOrCreate(
                [
                    'EmployeeNumber' => $employee->EmployeeNumber,
                    'AttendanceDate' => $date,
                    'Shift' => $shift,
                ],
                [
                    'Status' => $status,
                    'Notes' => $notes,
                ]
            );

            $count++;
        }

        $this->info("Imported {$count} roster rows.");
        return 0;
    }
}
