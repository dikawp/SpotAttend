@extends('layouts.app')
@section('title', 'Employee Dashboard')
@section('content')
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen">

        <!-- Dashboard Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Welcome back, {{ $userName }}! Here is a summary of your activities.</p>
            @if(!$employee)
                <div class="mt-4 p-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg dark:bg-yellow-200 dark:text-yellow-800" role="alert">
                    Warning: Your account is not yet linked to an employee profile. Please contact HR.
                </div>
            @endif
        </div>

        @if($employee)
        <!-- Statistics Summary (KPI Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            
            <!-- Card 1: Attendance Today -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 flex items-center space-x-4 transition-transform transform hover:scale-105">
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-500 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Status Today</p>
                    <p class="text-xl font-bold text-gray-800 dark:text-white">
                        @if($todayAttendance)
                            @if($todayAttendance->check_out)
                                Checked Out
                            @else
                                Checked In ({{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }})
                            @endif
                        @else
                            Not Checked In
                        @endif
                    </p>
                </div>
            </div>

            <!-- Card 2: Attendance This Month -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 flex items-center space-x-4 transition-transform transform hover:scale-105">
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full flex-shrink-0">
                    <svg class="w-6 h-6 text-green-500 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Attendance This Month</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $attendanceThisMonth }} <span class="text-sm font-normal text-gray-500">days</span></p>
                </div>
            </div>

            <!-- Card 3: Leaves Taken -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 flex items-center space-x-4 transition-transform transform hover:scale-105">
                <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-500 dark:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Leaves Taken (This Year)</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $leavesTakenThisYear }}</p>
                </div>
            </div>
        </div>

        <!-- Main Content (2 columns) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Attendance Action & Recent Leaves -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Quick Actions</h3>
                    <div class="flex gap-4">
                        <a href="{{ route('my.attendance.index') }}" class="flex-1 bg-blue-50 hover:bg-blue-100 dark:bg-gray-700 dark:hover:bg-gray-600 border border-blue-200 dark:border-gray-600 rounded-lg p-4 text-center transition group">
                            <svg class="w-8 h-8 mx-auto text-blue-600 dark:text-blue-400 mb-2 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="block font-medium text-blue-900 dark:text-blue-300">My Attendance</span>
                        </a>
                        <a href="{{ route('my-leaves.create') }}" class="flex-1 bg-green-50 hover:bg-green-100 dark:bg-gray-700 dark:hover:bg-gray-600 border border-green-200 dark:border-gray-600 rounded-lg p-4 text-center transition group">
                            <svg class="w-8 h-8 mx-auto text-green-600 dark:text-green-400 mb-2 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="block font-medium text-green-900 dark:text-green-300">Request Leave</span>
                        </a>
                    </div>
                </div>

                <!-- Recent Leave Requests -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">My Recent Leave Requests</h3>
                        <a href="{{ route('my-leaves.index') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Date</th>
                                    <th scope="col" class="px-6 py-3">Type</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLeaves as $leave)
                                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }} 
                                            @if($leave->start_date != $leave->end_date)
                                                - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                {{ $leave->reason }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($leave->status == 0)
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Pending</span>
                                            @elseif($leave->status == 1)
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Approved</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            You haven't made any leave requests yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Profile Summary & Holidays -->
            <div class="space-y-8">
                
                <!-- Profile Summary -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">My Profile</h3>
                    <div class="flex items-center space-x-4 mb-4">
                        @if($employee->photo)
                            <img class="h-16 w-16 rounded-full object-cover" src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->full_name }}">
                        @else
                            <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 font-bold text-xl flex-shrink-0">
                                {{ substr($employee->full_name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $employee->full_name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->position->name ?? 'No Position' }}</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <p><strong class="font-medium text-gray-700 dark:text-gray-300">Department:</strong> <br>{{ $employee->department->name ?? 'N/A' }}</p>
                        <p><strong class="font-medium text-gray-700 dark:text-gray-300">Office:</strong> <br>{{ $employee->officeLocation->name ?? $employee->department->officeLocation->name ?? 'Default Office' }}</p>
                    </div>
                </div>

                <!-- Upcoming Holidays -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Upcoming Holidays</h3>
                    <ul class="space-y-3">
                        @forelse($upcomingHolidays as $holiday)
                            <li class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg flex justify-between items-center border-l-4 border-indigo-400">
                                <div>
                                    <p class="font-semibold text-gray-700 dark:text-gray-200">{{ $holiday->description }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Company Holiday</p>
                                </div>
                                <span class="text-sm font-semibold text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($holiday->date)->format('M d, Y') }}</span>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500 dark:text-gray-400 italic">No upcoming holidays scheduled.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
