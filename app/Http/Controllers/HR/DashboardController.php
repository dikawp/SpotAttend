<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = \Carbon\Carbon::today();
        
        $totalEmployees = \App\Models\Employee::count();
        $activeEmployees = $totalEmployees; // Since there is no active status field currently
        
        $onLeaveToday = \App\Models\LeaveRequest::where('status', 1) // 1: Approved
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();
            
        $pendingApprovals = \App\Models\LeaveRequest::where('status', 0)->count(); // 0: Pending
        
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

        return view('hr.dashboard', compact(
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
}
