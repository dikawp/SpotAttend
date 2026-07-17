@extends('layouts.app')
@section('title', 'Superadmin Dashboard')
@section('content')
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen">

        <!-- Dashboard Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Welcome back, {{ $userName }}! Here is a summary of today's activities.</p>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistics Summary (KPI Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Card 1: Total Employees -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 flex items-center space-x-4 transition-transform transform hover:scale-105">
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-500 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.5-2.962a3.75 3.75 0 015.25 0m-5.25 0a3.75 3.75 0 00-5.25 0M12 19.5a3 3 0 01-3-3V12a3 3 0 013-3h.008c1.657 0 3 1.343 3 3v4.5a3 3 0 01-3 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Employees</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($totalEmployees) }}</p>
                </div>
            </div>

            <!-- Card 2: Active Employees -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 flex items-center space-x-4 transition-transform transform hover:scale-105">
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-500 dark:text-green-300" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Active Employees</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($activeEmployees) }}</p>
                </div>
            </div>

            <!-- Card 3: On Leave Today -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 flex items-center space-x-4 transition-transform transform hover:scale-105">
                <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-500 dark:text-yellow-300" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M-4.5 12h22.5" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">On Leave Today</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($onLeaveToday) }}</p>
                </div>
            </div>

            <!-- Card 4: Pending Approvals -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 flex items-center space-x-4 transition-transform transform hover:scale-105">
                <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                    <svg class="w-6 h-6 text-red-500 dark:text-red-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Pending Approvals</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($pendingApprovals) }}</p>
                </div>
            </div>
        </div>

        <!-- Main Content (2 columns) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Announcements & Employee List & HR Accounts -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Daftar Akun HR -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">HR Accounts List</h2>
                        <a href="{{ route('superadmin.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Add HR Account
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Created At</th>
                                    <th scope="col" class="px-6 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hrs as $hr)
                                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $hr->name }}
                                        </td>
                                        <td class="px-6 py-4">{{ $hr->email }}</td>
                                        <td class="px-6 py-4">{{ $hr->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('superadmin.edit', $hr) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                                            <form action="{{ route('superadmin.destroy', $hr) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this account?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No HR accounts found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Leave Request List -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Latest Leave Requests</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Employee Name</th>
                                    <th scope="col" class="px-6 py-3">Date</th>
                                    <th scope="col" class="px-6 py-3">Leave Type</th>
                                    <th scope="col" class="px-6 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestLeaveRequests as $leave)
                                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $leave->employee->full_name ?? 'Unknown' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $leave->start_date->format('M d') }} 
                                            @if($leave->start_date != $leave->end_date)
                                                - {{ $leave->end_date->format('M d, Y') }}
                                            @else
                                                , {{ $leave->start_date->format('Y') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                {{ $leave->reason }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('leaves.index') }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No recent leave requests found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Birthdays & Holidays -->
            <div class="space-y-8">
                <!-- Employee Birthdays -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Birthdays This Week</h3>
                    <ul class="space-y-4">
                        @forelse($birthdaysThisWeek as $employee)
                            <li class="flex items-center space-x-3">
                                @if($employee->photo)
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->full_name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 font-bold">
                                        {{ substr($employee->full_name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $employee->full_name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($employee->date_of_birth)->format('F d') }} 
                                        @if(\Carbon\Carbon::parse($employee->date_of_birth)->format('m-d') == \Carbon\Carbon::today()->format('m-d'))
                                            <span class="text-indigo-500 font-semibold">(Today)</span>
                                        @endif
                                    </p>
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500 dark:text-gray-400 italic">No birthdays this week.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Upcoming Holidays -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Upcoming Holidays</h3>
                    <ul class="space-y-3">
                        @forelse($upcomingHolidays as $holiday)
                            <li class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg flex justify-between items-center border-l-4 border-indigo-400">
                                <div>
                                    <p class="font-semibold text-gray-700 dark:text-gray-200">{{ $holiday->description }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Company Holiday</p>
                                </div>
                                <span class="text-sm font-semibold text-gray-800 dark:text-white">{{ $holiday->date->format('M d, Y') }}</span>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500 dark:text-gray-400 italic">No upcoming holidays scheduled.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
