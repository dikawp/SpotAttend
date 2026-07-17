<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperadminController extends Controller
{
    public function index()
    {
        $today = \Carbon\Carbon::today();
        $totalEmployees = \App\Models\Employee::count();
        $activeEmployees = $totalEmployees;
        
        $onLeaveToday = \App\Models\LeaveRequest::where('status', 1)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();
            
        $pendingApprovals = \App\Models\LeaveRequest::where('status', 0)->count();
        
        $latestLeaveRequests = \App\Models\LeaveRequest::with('employee')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $allEmployees = \App\Models\Employee::select('id', 'full_name', 'date_of_birth', 'photo')->get();
        $birthdaysThisWeek = $allEmployees->filter(function($emp) use ($today) {
            if (!$emp->date_of_birth) return false;
            $dob = \Carbon\Carbon::parse($emp->date_of_birth);
            $dobThisYear = $dob->copy()->year($today->year);
            if ($dobThisYear->isBefore($today)) {
                $dobThisYear->addYear();
            }
            return $dobThisYear->between($today, $today->copy()->addDays(7));
        })->sortBy(function($emp) use ($today) {
            $dob = \Carbon\Carbon::parse($emp->date_of_birth)->year($today->year);
            if ($dob->isBefore($today)) $dob->addYear();
            return $dob;
        })->take(5);

        $upcomingHolidays = \App\Models\Holiday::whereDate('date', '>=', $today)
            ->orderBy('date', 'asc')
            ->take(5)
            ->get();
            
        $userName = \Illuminate\Support\Facades\Auth::user()->name;
        
        $hrs = User::where('role', 1)->get();
        return view('superadmin.index', compact(
            'hrs',
            'totalEmployees',
            'activeEmployees',
            'onLeaveToday',
            'pendingApprovals',
            'latestLeaveRequests',
            'birthdaysThisWeek',
            'upcomingHolidays',
            'userName'
        ));
    }

    public function create()
    {
        return view('superadmin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 1, // 1 = HR
        ]);

        return redirect()->route('superadmin.index')->with('success', 'HR account successfully created.');
    }

    public function edit(User $superadmin)
    {
        if ($superadmin->role != 1) abort(403);
        $hr = $superadmin;
        return view('superadmin.edit', compact('hr'));
    }

    public function update(Request $request, User $superadmin)
    {
        if ($superadmin->role != 1) abort(403);
        $hr = $superadmin;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $hr->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $hr->name = $request->name;
        $hr->email = $request->email;
        if ($request->password) {
            $hr->password = Hash::make($request->password);
        }
        $hr->save();

        return redirect()->route('superadmin.index')->with('success', 'HR account successfully updated.');
    }

    public function destroy(User $superadmin)
    {
        if ($superadmin->role != 1) abort(403);
        $superadmin->delete();
        return redirect()->route('superadmin.index')->with('success', 'HR account successfully deleted.');
    }
}
