<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StaffController extends Controller
{
    public function index()
    {
        // Data dummy karyawan untuk absensi
        $employees = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'position' => 'Barista',
                'employee_id' => 'EMP001',
                'department' => 'Production',
                'status' => 'present',
                'check_in' => '08:00:00',
                'check_out' => null,
                'late_minutes' => 0,
                'overtime_hours' => 0
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'position' => 'Kasir',
                'employee_id' => 'EMP002',
                'department' => 'Front Office',
                'status' => 'present',
                'check_in' => '08:15:00',
                'check_out' => null,
                'late_minutes' => 15,
                'overtime_hours' => 0
            ],
            [
                'id' => 3,
                'name' => 'Bob Wilson',
                'position' => 'Kitchen Staff',
                'employee_id' => 'EMP003',
                'department' => 'Kitchen',
                'status' => 'absent',
                'check_in' => null,
                'check_out' => null,
                'late_minutes' => 0,
                'overtime_hours' => 0
            ],
            [
                'id' => 4,
                'name' => 'Alice Brown',
                'position' => 'Supervisor',
                'employee_id' => 'EMP004',
                'department' => 'Management',
                'status' => 'present',
                'check_in' => '07:45:00',
                'check_out' => null,
                'late_minutes' => 0,
                'overtime_hours' => 0
            ],
            [
                'id' => 5,
                'name' => 'Charlie Davis',
                'position' => 'Cleaner',
                'employee_id' => 'EMP005',
                'department' => 'Maintenance',
                'status' => 'on_leave',
                'check_in' => null,
                'check_out' => null,
                'late_minutes' => 0,
                'overtime_hours' => 0
            ],
            [
                'id' => 6,
                'name' => 'Diana Miller',
                'position' => 'Barista',
                'employee_id' => 'EMP006',
                'department' => 'Production',
                'status' => 'present',
                'check_in' => '08:30:00',
                'check_out' => '17:00:00',
                'late_minutes' => 30,
                'overtime_hours' => 1
            ]
        ];

        // Data absensi bulanan untuk statistik
        $monthlyStats = [
            'total_employees' => 6,
            'present_today' => 4,
            'absent_today' => 1,
            'on_leave_today' => 1,
            'late_today' => 2,
            'average_check_in' => '08:12',
            'total_overtime_hours' => 1
        ];

        return view('staff.index', compact('employees', 'monthlyStats'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string'
        ]);

        $currentTime = now()->format('H:i:s');
        $standardTime = '08:00:00';

        // Hitung keterlambatan
        $lateMinutes = 0;
        if ($currentTime > $standardTime) {
            $late = strtotime($currentTime) - strtotime($standardTime);
            $lateMinutes = floor($late / 60);
        }

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil dicatat',
            'data' => [
                'employee_id' => $request->employee_id,
                'check_in_time' => $currentTime,
                'late_minutes' => $lateMinutes,
                'status' => $lateMinutes > 0 ? 'late' : 'on_time'
            ]
        ]);
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string'
        ]);

        $currentTime = now()->format('H:i:s');
        $standardTime = '17:00:00';

        // Hitung overtime
        $overtimeHours = 0;
        if ($currentTime > $standardTime) {
            $overtime = strtotime($currentTime) - strtotime($standardTime);
            $overtimeHours = round($overtime / 3600, 1);
        }

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil dicatat',
            'data' => [
                'employee_id' => $request->employee_id,
                'check_out_time' => $currentTime,
                'overtime_hours' => $overtimeHours
            ]
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'status' => 'required|in:present,absent,on_leave,sick'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status karyawan berhasil diperbarui',
            'data' => [
                'employee_id' => $request->employee_id,
                'new_status' => $request->status
            ]
        ]);
    }

    public function getAttendanceReport(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Data dummy laporan absensi
        $report = [
            'period' => $month . '/' . $year,
            'summary' => [
                'total_working_days' => 22,
                'total_present' => 18,
                'total_absent' => 2,
                'total_late' => 5,
                'total_overtime_hours' => 15.5
            ],
            'details' => [
                [
                    'employee_id' => 'EMP001',
                    'name' => 'John Doe',
                    'present_days' => 20,
                    'absent_days' => 2,
                    'late_count' => 1,
                    'overtime_hours' => 5.0
                ],
                [
                    'employee_id' => 'EMP002',
                    'name' => 'Jane Smith',
                    'present_days' => 18,
                    'absent_days' => 4,
                    'late_count' => 3,
                    'overtime_hours' => 2.5
                ]
            ]
        ];

        return response()->json([
            'success' => true,
            'report' => $report
        ]);
    }
}
