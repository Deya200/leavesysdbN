<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Employee;
class GradeController extends Controller
{
    /**
     * Display a listing of grades.
     */
    public function index()
    {
        $grades = Grade::all(); // Fetch all grades
        return view('grades.index', compact('grades')); // Pass grades to the view
    }

    /**
     * Show the form for creating a new grade.
     */
    public function create()
    {
        return view('grades.create');
    }

    /**
     * Store a newly created grade in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'GradeName' => 'required|string|max:100',
            'AnnualLeaveDays' => 'required|integer|min:0', // Validate leave days
        ]);

        Grade::create($request->all());

        return redirect()->route('grades.index')->with('success', 'Grade added successfully!');
    }

    /**
     * Show the form for editing a grade.
     */
    public function edit($GradeID)
    {
        $grade = Grade::findOrFail($GradeID);
        return view('grades.edit', compact('grade'));
    }

    /**
     * Update a grade in storage.
     */
    public function update(Request $request, $GradeID)
    {
        $request->validate([
            'GradeName' => 'required|string|max:100',
            'AnnualLeaveDays' => 'required|integer|min:0', // Validate leave days
        ]);

        $grade = Grade::findOrFail($GradeID);
        $grade->update($request->all());

        return redirect()->route('grades.index')->with('success', 'Grade updated successfully!');
    }

    /**
     * Remove a grade from storage.
     */
    public function destroy($GradeID)
    {
        $grade = Grade::findOrFail($GradeID);
        $grade->delete();

        return redirect()->route('grades.index')->with('success', 'Grade deleted successfully!');
    }
}
