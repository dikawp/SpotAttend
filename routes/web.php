<?php

use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\HR\AttendanceMonitorController;
use App\Http\Controllers\HR\DashboardController;
use App\Http\Controllers\HR\DepartmentController;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\HR\HolidayController;
use App\Http\Controllers\HR\LeaveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserAttendanceController;
use App\Http\Controllers\UserLeaveController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Attendance
    Route::get('/my-attendance', [UserAttendanceController::class, 'index'])->name('my.attendance.index');
    Route::post('/my-attendance/check-in', [UserAttendanceController::class, 'checkIn'])->name('my.attendance.checkin');
    Route::post('/my-attendance/check-out', [UserAttendanceController::class, 'checkOut'])->name('my.attendance.checkout');

    // User Leaves
    Route::resource('my-leaves', UserLeaveController::class);
});

Route::get('/dashboard', [\App\Http\Controllers\EmployeeDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified', 'role:2'])->group(function () {
    Route::resource('superadmin', \App\Http\Controllers\SuperadminController::class)->except(['show']);
});

Route::middleware(['auth', 'verified', 'role:1,2'])->group(function () {
    Route::get('/hr/dashboard', [DashboardController::class, 'index'])->name('hr.dashboard');

    // Departments
    Route::resource('departments', DepartmentController::class);
    Route::get('/departments/{department}/positions', [DepartmentController::class, 'getPositions'])->name('departments.positions');

    // Employees
    Route::resource('employees', EmployeeController::class);

    // Holidays
    Route::resource('holidays', HolidayController::class);

    // Attendance
    Route::resource('attendances', AttendanceController::class);
    Route::get('monitoring', [AttendanceMonitorController::class, 'Index'])->name('attendances.monitor');

    // Leaves
    Route::resource('leaves', LeaveController::class);

    // Office Locations
    Route::get('office-locations/{office_location}/assign', [\App\Http\Controllers\OfficeLocationController::class, 'assign'])->name('office-locations.assign');
    Route::post('office-locations/{office_location}/assign', [\App\Http\Controllers\OfficeLocationController::class, 'storeAssign'])->name('office-locations.storeAssign');
    Route::resource('office-locations', \App\Http\Controllers\OfficeLocationController::class);
});


require __DIR__ . '/auth.php';
