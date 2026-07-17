<?php

namespace App\Http\Controllers;

use App\Models\OfficeLocation;
use Illuminate\Http\Request;

class OfficeLocationController extends Controller
{
    public function index()
    {
        $locations = OfficeLocation::all();
        return view('hr.attendances.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('hr.attendances.locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:1',
        ]);
        
        $validated['is_default'] = $request->has('is_default');
        
        if (OfficeLocation::count() === 0) {
            $validated['is_default'] = true;
        }

        if ($validated['is_default']) {
            OfficeLocation::query()->update(['is_default' => false]);
        }

        OfficeLocation::create($validated);

        return redirect()->route('office-locations.index')->with('success', 'Office Location created successfully.');
    }

    public function edit(OfficeLocation $officeLocation)
    {
        return view('hr.attendances.locations.edit', compact('officeLocation'));
    }

    public function update(Request $request, OfficeLocation $officeLocation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:1',
        ]);

        $validated['is_default'] = $request->has('is_default');

        if ($validated['is_default']) {
            OfficeLocation::where('id', '!=', $officeLocation->id)->update(['is_default' => false]);
        }

        $officeLocation->update($validated);

        return redirect()->route('office-locations.index')->with('success', 'Office Location updated successfully.');
    }

    public function destroy(OfficeLocation $officeLocation)
    {
        $officeLocation->delete();
        return redirect()->route('office-locations.index')->with('success', 'Office Location deleted successfully.');
    }

    public function assign(OfficeLocation $officeLocation)
    {
        $departments = \App\Models\Department::all();
        $employees = \App\Models\Employee::all();
        
        return view('hr.attendances.locations.assign', compact('officeLocation', 'departments', 'employees'));
    }

    public function storeAssign(Request $request, OfficeLocation $officeLocation)
    {
        $validated = $request->validate([
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:departments,id',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        if (!empty($validated['department_ids'])) {
            \App\Models\Department::whereIn('id', $validated['department_ids'])
                ->update(['office_location_id' => $officeLocation->id]);
        }

        if (!empty($validated['employee_ids'])) {
            \App\Models\Employee::whereIn('id', $validated['employee_ids'])
                ->update(['office_location_id' => $officeLocation->id]);
        }

        return redirect()->route('office-locations.index')->with('success', 'Location assigned successfully.');
    }
}
