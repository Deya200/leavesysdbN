<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use App\Services\LeaveActivationService;
use App\Services\CarryOverService;

Schedule::call(function () {
    app(LeaveActivationService::class)->activateLeaves();
})->daily()->name('activate-leaves');

Schedule::call(function () {
    app(CarryOverService::class)->processAnnualCarryOver();
})->yearlyOn(7, 1, '00:00')->name('process-carry-over'); // Run on July 1st

// Calculate monthly locum work on the 26th of each month
Schedule::command('locum:calculate-monthly')
    ->monthlyOn(26, '09:00')
    ->name('calculate-monthly-locum-work');
