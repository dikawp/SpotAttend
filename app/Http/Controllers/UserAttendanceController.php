<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAttendanceController extends Controller
{
    /**
     * Menampilkan halaman absensi untuk karyawan yang login.
     */
    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Cari data absensi hari ini
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->where('date', today()->toDateString())
            ->first();

        // Ambil riwayat absensi bulan ini
        $monthlyAttendance = Attendance::where('employee_id', $employee->id)
            ->whereMonth('date', today()->month)
            ->whereYear('date', today()->year)
            ->orderBy('date', 'desc')
            ->get();

        // Ambil lokasi
        $officeLocation = $employee->officeLocation ?? $employee->department->officeLocation ?? \App\Models\OfficeLocation::where('is_default', true)->first() ?? \App\Models\OfficeLocation::first();

        return view('userAttendance.index', compact('todayAttendance', 'monthlyAttendance', 'officeLocation', 'employee'));
    }

    /**
     * Proses Check-in
     */
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        if (!$user->employee) {
            abort(403, 'Employee data not found.');
        }
        $employee = $user->employee;

        $alreadyCheckedIn = Attendance::where('employee_id', $employee->id)
            ->where('date', today()->toDateString())
            ->exists();
        if ($alreadyCheckedIn) {
            return redirect()->route('my.attendance.index')->with('error', 'You have already checked in today.');
        }

        $checkInTime = now();
        $status = 'On Time';

        if ($employee->schedule_start_time) {
            $scheduleStartTimeToday = today()->setTimeFromTimeString($employee->schedule_start_time);
            $lateThreshold = $scheduleStartTimeToday->copy()->addMinutes(15); // Toleransi 15 menit

            if ($checkInTime->isAfter($lateThreshold)) {
                $status = 'Late';
            }
        }

        // Buat record absensi
        Attendance::create([
            'employee_id' => $employee->id,
            'date' => today(),
            'check_in' => $checkInTime,
            'status' => $status,
        ]);

        return redirect()->route('my.attendance.index')->with('success', 'Check-in successful! Status: ' . $status);
    }

    /**
     * Proses Check-out
     */
    public function checkOut(Request $request)
    {
        $user = Auth::user();

        if (!$user->employee) {
            abort(403, 'Employee data not found.');
        }

        $employee = $user->employee;

        // Cari record absensi hari ini yang belum check-out
        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', today()->toDateString())
            ->whereNull('check_out')
            ->first();

        if (!$attendance) {
            return redirect()->route('my.attendance.index')
                ->with('error', 'No active check-in found to check-out.');
        }

        // --- Waktu checkout ---
        $checkOutTime = now(config('app.timezone'));
        $overtimeMinutes = 0;

        // --- Logika perhitungan lembur ---
        if ($employee->schedule_end_time) {
            $scheduleEndTimeToday = $checkOutTime->copy()->setTimeFromTimeString($employee->schedule_end_time);

            if ($checkOutTime->isAfter($scheduleEndTimeToday)) {
                $diffMinutes = (int) round($scheduleEndTimeToday->diffInMinutes($checkOutTime));


                // Hitung lembur hanya jika lebih dari 60 menit
                if ($diffMinutes > 60) {
                    $overtimeMinutes = $diffMinutes;
                }
            }

            // dd($overtimeMinutes);
        }

        // Update record absensi
        $attendance->update([
            'check_out' => $checkOutTime,
            'overtime_minutes' => $overtimeMinutes,
        ]);

        // --- Pesan sukses ---
        $message = 'Check-out successful!';
        if ($overtimeMinutes > 0) {
            $hours = floor($overtimeMinutes / 60);
            $minutes = $overtimeMinutes % 60;
            $message .= sprintf(' Overtime: %d hr %02d min.', $hours, $minutes);
        }

        return redirect()->route('my.attendance.index')->with('success', $message);
    }
}
