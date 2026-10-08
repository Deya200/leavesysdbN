<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Grade;
use App\Models\Employee;

class PositionController extends Controller
{
    /**
     * Display a listing of positions.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $positions = Position::with('grade')
            ->when($search, fn($q) => $q
                ->where('PositionName', 'like', "%{$search}%")
                ->orWhereHas('grade', fn($q2) => $q2->where('GradeName', 'like', "%{$search}%"))
            )
            ->orderBy('PositionName')
            ->paginate(15)
            ->withQueryString();
        return view('positions.index', compact('positions', 'search'));
    }

    /**
     * Show the form for creating a new position.
     */
    public function create()
    {
        $grades = Grade::all(); // Fetch grades for dropdown
        return view('positions.create', compact('grades'));
    }

    /**
     * Store a newly created position in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'PositionName' => 'required|string|max:150',
            'GradeID' => 'required|exists:grades,GradeID',
        ]);

        Position::create($request->all());

        return redirect()->route('positions.index')->with('success', 'Position added successfully!');
    }

    /**
     * Show the form for editing a position.
     */
    public function edit($PositionID)
    {
        $position = Position::findOrFail($PositionID);
        $grades = Grade::all(); // Fetch grades for dropdown
        return view('positions.edit', compact('position', 'grades'));
    }

    /**
     * Update the specified position in storage.
     */
    public function update(Request $request, $PositionID)
    {
        $request->validate([
            'PositionName' => 'required|string|max:150',
            'GradeID' => 'required|exists:grades,GradeID',
        ]);

        $position = Position::findOrFail($PositionID);
        $position->update($request->all());

        return redirect()->route('positions.index')->with('success', 'Position updated successfully!');
    }

    /**
     * Remove the specified position from storage.
     */
    public function destroy($PositionID)
    {
        $position = Position::findOrFail($PositionID);
        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Position deleted successfully!');
    }
}
