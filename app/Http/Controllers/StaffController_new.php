<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StaffController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        $currentEmployeeId = $user['role'] === 'Staff' ? 'EMP003' : 'EMP001'; // Dummy mapping

        // Data dummy absensi karyawan yang sedang login
        $myAttendance = [
            'employee_id' => $currentEmployeeId,
            'employee_name' => $user['name'],
            'position' => $user['role'],
            'today_date' => date('Y-m-d'),
            'check_in' => null,
            'check_out' => null,
            'working_hours' => 0,
            'late_minutes' => 0,
            'overtime_hours' => 0,
            'status' => 'not_checked_in',
            'notes' => '',
            'break_start' => null,
            'break_end' => null,
            'break_duration' => 0
        ];

        // Simulasi check jika sudah check-in hari ini
        $hasCheckedInToday = rand(0, 1); // Random untuk demo
        if ($hasCheckedInToday) {
            $myAttendance['check_in'] = '08:15:00';
            $myAttendance['status'] = 'checked_in';
            $myAttendance['late_minutes'] = 15;
        }

        // Data absensi tim/karyawan lain (untuk staff yang bisa melihat tim)
        $teamAttendances = [
            [
                'id' => 1,
                'employee_id' => 'EMP001',
                'employee_name' => 'John Doe',
                'position' => 'Barista',
                'date' => date('Y-m-d'),
                'check_in' => '08:00:00',
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => 0,
                'overtime_hours' => 0,
                'status' => 'checked_in',
                'notes' => ''
            ],
            [
                'id' => 2,
                'employee_id' => 'EMP002',
                'employee_name' => 'Jane Smith',
                'position' => 'Kasir',
                'date' => date('Y-m-d'),
                'check_in' => '08:15:00',
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => 15,
                'overtime_hours' => 0,
                'status' => 'checked_in',
                'notes' => 'Terlambat karena macet'
            ],
            [
                'id' => 3,
                'employee_id' => 'EMP004',
                'employee_name' => 'Alice Brown',
                'position' => 'Barista',
                'date' => date('Y-m-d'),
                'check_in' => '07:45:00',
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => 0,
                'overtime_hours' => 0,
                'status' => 'checked_in',
                'notes' => ''
            ],
            [
                'id' => 4,
                'employee_id' => 'EMP005',
                'employee_name' => 'Charlie Davis',
                'position' => 'Cleaning Staff',
                'date' => date('Y-m-d'),
                'check_in' => null,
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => 0,
                'overtime_hours' => 0,
                'status' => 'not_checked_in',
                'notes' => ''
            ]
        ];

        // Riwayat absensi minggu ini
        $weeklyHistory = [
            [
                'date' => date('Y-m-d', strtotime('-4 days')),
                'check_in' => '08:00:00',
                'check_out' => '17:00:00',
                'working_hours' => 9.0,
                'late_minutes' => 0,
                'overtime_hours' => 1.0,
                'status' => 'present'
            ],
            [
                'date' => date('Y-m-d', strtotime('-3 days')),
                'check_in' => '08:10:00',
                'check_out' => '16:45:00',
                'working_hours' => 8.58,
                'late_minutes' => 10,
                'overtime_hours' => 0,
                'status' => 'present'
            ],
            [
                'date' => date('Y-m-d', strtotime('-2 days')),
                'check_in' => null,
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => 0,
                'overtime_hours' => 0,
                'status' => 'sick_leave'
            ],
            [
                'date' => date('Y-m-d', strtotime('-1 days')),
                'check_in' => '07:55:00',
                'check_out' => '17:30:00',
                'working_hours' => 9.58,
                'late_minutes' => 0,
                'overtime_hours' => 1.58,
                'status' => 'present'
            ]
        ];

        // Statistik
        $stats = [
            'team_present' => collect($teamAttendances)->where('status', 'checked_in')->count(),
            'team_total' => count($teamAttendances),
            'my_hours_today' => $myAttendance['working_hours'],
            'my_hours_week' => collect($weeklyHistory)->sum('working_hours'),
            'my_late_count_week' => collect($weeklyHistory)->where('late_minutes', '>', 0)->count(),
            'my_overtime_week' => collect($weeklyHistory)->sum('overtime_hours')
        ];

        return view('staff.index', compact('myAttendance', 'teamAttendances', 'weeklyHistory', 'stats'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string|max:255'
        ]);

        $user = Session::get('user');
        $currentTime = now()->format('H:i:s');
        $standardTime = '08:00:00';

        // Hitung keterlambatan
        $lateMinutes = 0;
        if ($currentTime > $standardTime) {
            $lateMinutes = round((strtotime($currentTime) - strtotime($standardTime)) / 60);
        }

        // Simulasi insert ke database
        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil!',
            'data' => [
                'employee_name' => $user['name'],
                'check_in_time' => $currentTime,
                'late_minutes' => $lateMinutes,
                'date' => date('Y-m-d'),
                'notes' => $request->notes
            ]
        ]);
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string|max:255'
        ]);

        $user = Session::get('user');
        $currentTime = now()->format('H:i:s');
        $checkInTime = '08:15:00'; // Simulasi dari session/database

        // Hitung jam kerja
        $workingHours = round((strtotime($currentTime) - strtotime($checkInTime)) / 3600, 2);
        $standardHours = 8;
        $overtimeHours = max(0, $workingHours - $standardHours);

        // Simulasi update database
        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil!',
            'data' => [
                'employee_name' => $user['name'],
                'check_out_time' => $currentTime,
                'working_hours' => $workingHours,
                'overtime_hours' => $overtimeHours,
                'date' => date('Y-m-d'),
                'notes' => $request->notes
            ]
        ]);
    }

    public function requestLeave(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|in:sick,annual,emergency',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500'
        ]);

        $user = Session::get('user');

        // Simulasi submit request cuti
        return response()->json([
            'success' => true,
            'message' => 'Permohonan cuti berhasil diajukan dan menunggu persetujuan manager',
            'data' => [
                'employee_name' => $user['name'],
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'status' => 'pending'
            ]
        ]);
    }

    public function break(Request $request)
    {
        $request->validate([
            'type' => 'required|in:start,end'
        ]);

        $user = Session::get('user');
        $currentTime = now()->format('H:i:s');

        if ($request->type === 'start') {
            $message = 'Break dimulai';
        } else {
            $message = 'Break selesai';
            // Simulasi hitung durasi break
            $breakStart = '12:00:00'; // Dari session/database
            $breakDuration = round((strtotime($currentTime) - strtotime($breakStart)) / 60);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'employee_name' => $user['name'],
                'break_time' => $currentTime,
                'break_duration' => isset($breakDuration) ? $breakDuration : 0,
                'type' => $request->type
            ]
        ]);
    }
}
