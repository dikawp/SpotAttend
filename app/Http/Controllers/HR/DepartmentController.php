<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use App\Models\OfficeLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Department::query()
            ->withCount('positions');

        $query->when($search, function ($q, $searchText) {
            $q->where('name', 'like', "%{$searchText}%")
                ->orWhere('description', 'like', "%{$searchText}%");
        });

        $query->latest();

        $departments = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('hr.departments.department_table', compact('departments'))->render();
        }

        return view('hr.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locations = OfficeLocation::all();
        return view('hr.departments.create', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
            'office_location_id' => 'nullable|exists:office_locations,id',
            'positions' => 'nullable|array', //
            'positions.*.name' => 'required|string|max:255',
            'positions.*.job_description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $department = Department::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'office_location_id' => $validated['office_location_id'] ?? null,
            ]);

            // Positions
            if (!empty($validated['positions'])) {
                foreach ($validated['positions'] as $positionData) {
                    $department->positions()->create([
                        'name' => $positionData['name'],
                        'job_description' => $positionData['job_description'] ?? null,
                    ]);
                }
            }

            DB::commit();
            toast('Department '.$request->name.' added sucessfuly','success');
            return redirect()->route('departments.index')->with('success', 'Department and positions created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            toast('Failed to create department. Error: ' . $e->getMessage(),'error');
            return back()->with('error', 'Failed to create department. Please try again. Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $department = Department::findOrFail($id);
        $locations = OfficeLocation::all();

        return view('hr.departments.edit', compact('department', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
            'description' => 'nullable|string',
            'office_location_id' => 'nullable|exists:office_locations,id',
            'positions' => 'nullable|array',
            'positions.*.id' => 'nullable|integer|exists:positions,id',
            'positions.*.name' => 'required_with:positions.*.id|string|max:255',
            'positions.*.job_description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $department->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'office_location_id' => $validated['office_location_id'] ?? null,
            ]);

            $existingPositionIds = $department->positions()->pluck('id')->toArray();
            $incomingPositionIds = [];

            foreach ($validated['positions'] ?? [] as $positionData) {
                $position = $department->positions()->updateOrCreate(
                    ['id' => $positionData['id'] ?? null],
                    $positionData
                );
                $incomingPositionIds[] = $position->id;
            }

            $idsToDelete = array_diff($existingPositionIds, $incomingPositionIds);

            if (!empty($idsToDelete)) {
                Position::destroy($idsToDelete);
            }

            DB::commit();
            toast('Department '.$request->name.' updated sucessfuly','success');
            return redirect()->route('departments.index')->with('success', 'Department and positions updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('Error', 'Cannot update departmen, there are employees associated with the deleted position.');
            return back()->with('error', 'Failed to update department. Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);

        if ($department->employees()->exists() || $department->positions()->exists()) {
            alert()->error('Error', 'Cannot delete department. It is still associated with employees or positions.');
            return back();
        }

        $department->delete();
        toast('Department '.$department->name.' deleted sucessfuly','success');
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }

    public function getPositions(Department $department)
    {
        return response()->json($department->positions);
    }
}
