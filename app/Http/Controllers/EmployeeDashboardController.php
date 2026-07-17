<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Holiday;
use App\Models\OfficeLocation;
use Carbon\Carbon;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role == 2) {
            return redirect()->route('superadmin.index');
        } elseif ($user->role == 1) {
            return redirect()->route('hr.dashboard');
        }

        $employee = $user->employee;
        $today = Carbon::today();

        if (!$employee) {
            return view('dashboard', [
                'employee' => null,
                'userName' => $user->name,
                'todayAttendance' => null,
                'recentLeaves' => [],
                'upcomingHolidays' => [],
                'leavesTakenThisYear' => 0,
                'attendanceThisMonth' => 0,
            ]);
        }

        // Attendance Today
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        // Recent Leaves
        $recentLeaves = LeaveRequest::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Upcoming Holidays
        $upcomingHolidays = Holiday::whereDate('date', '>=', $today)
            ->orderBy('date', 'asc')
            ->take(5)
            ->get();

        // Leaves taken this year (approved)
        $leavesTakenThisYear = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', 1)
            ->whereYear('start_date', $today->year)
            ->count();

        // Attendance this month
        $attendanceThisMonth = Attendance::where('employee_id', $employee->id)
            ->whereMonth('date', $today->month)
            ->whereYear('date', $today->year)
            ->count();

        return view('dashboard', [
            'employee' => $employee,
            'userName' => $user->name,
            'todayAttendance' => $todayAttendance,
            'recentLeaves' => $recentLeaves,
            'upcomingHolidays' => $upcomingHolidays,
            'leavesTakenThisYear' => $leavesTakenThisYear,
            'attendanceThisMonth' => $attendanceThisMonth,
        ]);
    }
}
