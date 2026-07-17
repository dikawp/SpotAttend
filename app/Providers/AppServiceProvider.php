<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('layouts.components.navbar', function ($view) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                $user = \Illuminate\Support\Facades\Auth::user();
                $notifications = collect();
                $unreadCount = 0;

                if (in_array($user->role, [1, 2])) { // HR & Superadmin
                    if (\App\Models\OfficeLocation::count() === 0) {
                        $notifications->push([
                            'message' => 'Office location is not set! Employees cannot check in. Please set up a location.',
                            'time' => 'Action Required',
                            'link' => route('office-locations.index')
                        ]);
                        $unreadCount++;
                    }
                }

                if ($user->role == 1) { // HR
                    $pendingLeaves = \App\Models\LeaveRequest::with('employee')
                        ->where('status', 0)
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                        
                    foreach ($pendingLeaves as $leave) {
                        $notifications->push([
                            'message' => 'New leave request from ' . ($leave->employee->full_name ?? 'Employee'),
                            'time' => $leave->created_at->diffForHumans(),
                            'link' => route('leaves.index')
                        ]);
                        $unreadCount++;
                    }
                } elseif ($user->role == 0) { // Karyawan
                    $employee = \App\Models\Employee::where('user_id', $user->id)->first();
                    if ($employee) {
                        $processedLeaves = \App\Models\LeaveRequest::where('employee_id', $employee->id)
                            ->whereIn('status', [1, 2])
                            ->orderBy('updated_at', 'desc')
                            ->take(5)
                            ->get();
                            
                        foreach ($processedLeaves as $leave) {
                            $status = $leave->status == 1 ? 'Approved' : 'Rejected';
                            $notifications->push([
                                'message' => 'Your leave request has been ' . $status,
                                'time' => $leave->updated_at->diffForHumans(),
                                'link' => route('my-leaves.index')
                            ]);
                        }
                        $unreadCount = $processedLeaves->count();
                    }
                }
                
                $view->with('navbarNotifications', $notifications)->with('navbarUnreadCount', $unreadCount);
            }
        });
    }
}
