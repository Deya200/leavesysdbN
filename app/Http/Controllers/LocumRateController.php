<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\LocumRate;
use App\Models\LocumSession;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocumRateController extends Controller
{
    public function index(): View
    {
        $rates = LocumRate::with('department')->get();
        $departments = Department::all();

        $currentMonth = now();
        $locumSessionsThisMonth = LocumSession::whereYear('session_date', $currentMonth->year)
            ->whereMonth('session_date', $currentMonth->month)
            ->get();

        $currentMonthSpend = $locumSessionsThisMonth->sum(function ($session) {
            return $session->total_earnings ?? ($session->hours_worked * ($session->hourly_rate ?? 2000));
        });

        $activeRateCount = $rates->where('is_active', true)->count();
        $departmentRateCount = $rates->pluck('DepartmentID')->unique()->count();

        return view('locum-rates.index', compact(
            'rates',
            'departments',
            'currentMonthSpend',
            'locumSessionsThisMonth',
            'activeRateCount',
            'departmentRateCount'
        ));
    }

    public function create(): View
    {
        $departments = Department::all();

        return view('locum-rates.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'DepartmentID' => 'required|exists:departments,DepartmentID',
            'position_type' => 'required|string|max:255',
            'shift' => 'required|in:day,night',
            'daily_rate' => 'required|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'is_active' => 'boolean',
        ]);

        // Check if rate already exists for this department, position type, and shift
        $existingRate = LocumRate::where('DepartmentID', $validated['DepartmentID'])
            ->where('position_type', $validated['position_type'])
            ->where('shift', $validated['shift'])
            ->first();

        if ($existingRate) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A rate already exists for this department, position type, and shift. Please edit the existing rate instead.');
        }

        LocumRate::create($validated);

        return redirect()->route('admin.locum_rates.index')
            ->with('success', 'Locum rate created successfully.');
    }

    public function edit(LocumRate $locumRate): View
    {
        $departments = Department::all();

        return view('locum-rates.edit', compact('locumRate', 'departments'));
    }

    public function update(Request $request, LocumRate $locumRate): RedirectResponse
    {
        $validated = $request->validate([
            'DepartmentID' => 'required|exists:departments,DepartmentID',
            'position_type' => 'required|string|max:255',
            'shift' => 'required|in:day,night',
            'daily_rate' => 'required|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'is_active' => 'boolean',
        ]);

        $locumRate->update($validated);

        return redirect()->route('admin.locum_rates.index')
            ->with('success', 'Locum rate updated successfully.');
    }

    public function destroy(LocumRate $locumRate): RedirectResponse
    {
        $locumRate->delete();

        return redirect()->route('admin.locum_rates.index')
            ->with('success', 'Locum rate deleted successfully.');
    }
}
