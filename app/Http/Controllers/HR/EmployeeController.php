<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use App\Models\OfficeLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $departmentId = $request->query('department_id');
        $positionId = $request->query('position_id');

        // Query 
        $employees = Employee::with(['user', 'department', 'position'])
            ->whereHas('user', fn($q) => $q->whereIn('role', [0, 1]))
            ->when($search, function ($q) use ($search) {
                $term = "%{$search}%";
                $q->where(function ($sub) use ($term) {
                    $sub->where('full_name', 'ILIKE', $term)
                        ->orWhereHas('user', fn($u) => $u->where('email', 'ILIKE', $term))
                        ->orWhereHas('department', fn($d) => $d->where('name', 'ILIKE', $term))
                        ->orWhereHas('position', fn($p) => $p->where('name', 'ILIKE', $term));
                });
            })
            ->when($departmentId, fn($q) => $q->where('department_id', $departmentId))
            ->when($positionId, fn($q) => $q->where('position_id', $positionId))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $departments = $request->ajax() ? [] : Department::orderBy('name')->get();

        if ($request->ajax()) {
            return view('hr.employees.employee_table', compact('employees'))->render();
        }

        return view('hr.employees.index', compact('employees', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('name')->get();
        $locations = OfficeLocation::all();

        return view('hr.employees.create', compact('departments', 'positions', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateEmployee($request);
        $photoPath = null;

        try {
            DB::beginTransaction();

            $userEmail = $request->email ?? ($validated['nik'] . '@hris.local');

            $userRole = (auth()->user()->role === 2 && isset($validated['role'])) ? $validated['role'] : 0;
            $user = User::create([
                'name' => $validated['name'],
                'email' => $userEmail,
                'password' => Hash::make($validated['password']),
                'role' => $userRole, // assigned role or default user
            ]);

            $photoPath = $this->handlePhotoUpload($request);

            Employee::create(array_merge(
                $this->extractEmployeeData($validated),
                [
                    'user_id' => $user->id,
                    'photo' => $photoPath,
                ]
            ));

            DB::commit();

            toast('Employee ' . $request->full_name . ' added successfully', 'success');

            return redirect()
                ->route('employees.index')
                ->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            toast('Failed to add employee: ' . $e->getMessage(), 'error');

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Employee::with(['user', 'department', 'position'])->findOrFail($id);
        return view('hr.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = Employee::with(['user'])->findOrFail($id);
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('name')->get();
        $locations = OfficeLocation::all();

        return view('hr.employees.edit', compact('employee', 'departments', 'positions', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);
        $validated = $this->validateEmployee($request, $employee);

        $userEmail = $request->email ?? ($validated['nik'] . '@hris.local');

        $user = $employee->user;
        $userFillData = [
            'name' => $validated['name'],
            'email' => $userEmail,
        ];
        
        if (auth()->user()->role === 2 && isset($validated['role'])) {
            $userFillData['role'] = $validated['role'];
        }

        $user->fill($userFillData);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Upload foto baru jika ada
        $photoPath = $this->handlePhotoUpload($request, $employee->photo);

        $employee->update(array_merge(
            $this->extractEmployeeData($validated),
            ['photo' => $photoPath ?? $employee->photo]
        ));

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);

        // Hapus foto jika ada
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        // Hapus user (otomatis delete employee via cascade jika diatur)
        $employee->user->delete();

        toast('Employee ' . $employee->full_name . ' deleted sucessfuly', 'success');

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    private function validateEmployee(Request $request, ?Employee $employee = null): array
    {
        $userId = $employee?->user_id;
        $employeeId = $employee?->id;

        return $request->validate([
            // User Info
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => $employee ? 'nullable|string|min:8' : 'required|string|min:8',
            'role' => 'nullable|integer|in:0,1',

            // Personal Info
            'full_name' => 'required|string|max:255',
            'nik' => ['required', 'string', 'max:50', Rule::unique('employees')->ignore($employeeId)],
            'phone_number' => ['nullable', 'string', 'max:20', Rule::unique('employees')->ignore($employeeId)],
            'bank' => 'nullable|string|max:50',
            'emergency' => 'nullable|string|max:20',
            'place_of_birth' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'marital_status' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Employment Info
            'hire_date' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'schedule_start_time' => 'required|date_format:H:i',
            'schedule_end_time' => 'required|date_format:H:i',
            'contract_type' => ['required', 'integer', Rule::in([0, 1, 2])],
            'annual_leave_days' => 'nullable|integer',
            'office_location_id' => 'nullable|exists:office_locations,id',
        ]);
    }

    private function extractEmployeeData(array $validated): array
    {
        return [
            'full_name' => $validated['full_name'],
            'nik' => $validated['nik'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'bank' => $validated['bank'] ?? null,
            'emergency' => $validated['emergency'] ?? null,
            'place_of_birth' => $validated['place_of_birth'] ?? null,
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'marital_status' => $validated['marital_status'] ?? null,
            'address' => $validated['address'] ?? null,
            'hire_date' => $validated['hire_date'],
            'department_id' => $validated['department_id'],
            'position_id' => $validated['position_id'],
            'schedule_start_time' => $validated['schedule_start_time'],
            'schedule_end_time' => $validated['schedule_end_time'],
            'contract_type' => $validated['contract_type'],
            'annual_leave_days' => $validated['annual_leave_days'],
            'office_location_id' => $validated['office_location_id'] ?? null,
        ];
    }

    private function handlePhotoUpload(Request $request, ?string $oldPhoto = null): ?string
    {
        if (!$request->hasFile('photo')) {
            return null;
        }

        if ($oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return $request->file('photo')->store('photos', 'public');
    }
}
