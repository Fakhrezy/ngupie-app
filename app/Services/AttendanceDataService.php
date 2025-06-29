<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class AttendanceDataService
{
    /**
     * Get shared attendance data yang bisa diakses oleh staff dan manager
     */
    public static function getSharedAttendanceData()
    {
        // Data dummy yang akan dishare antara staff dan manager
        return [
            [
                'id' => 1,
                'employee_id' => 'EMP001',
                'employee_name' => 'John Doe',
                'position' => 'Barista',
                'department' => 'Production',
                'date' => date('Y-m-d'),
                'check_in' => '08:00:00',
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => 0,
                'overtime_hours' => 0,
                'status' => 'checked_in',
                'notes' => '',
                'break_start' => null,
                'break_end' => null,
                'break_duration' => 0,
                'approved_by' => null,
                'last_updated' => now()->format('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'employee_id' => 'EMP002',
                'employee_name' => 'Jane Smith',
                'position' => 'Kasir',
                'department' => 'Front Office',
                'date' => date('Y-m-d'),
                'check_in' => '08:15:00',
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => 15,
                'overtime_hours' => 0,
                'status' => 'checked_in',
                'notes' => 'Terlambat karena macet',
                'break_start' => null,
                'break_end' => null,
                'break_duration' => 0,
                'approved_by' => null,
                'last_updated' => now()->format('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'employee_id' => 'EMP003',
                'employee_name' => 'Staff User',
                'position' => 'Staff',
                'department' => 'Management',
                'date' => date('Y-m-d'),
                'check_in' => null,
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => 0,
                'overtime_hours' => 0,
                'status' => 'not_checked_in',
                'notes' => '',
                'break_start' => null,
                'break_end' => null,
                'break_duration' => 0,
                'approved_by' => null,
                'last_updated' => now()->format('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'employee_id' => 'EMP004',
                'employee_name' => 'Alice Brown',
                'position' => 'Barista',
                'department' => 'Production',
                'date' => date('Y-m-d'),
                'check_in' => '07:45:00',
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => 0,
                'overtime_hours' => 0,
                'status' => 'checked_in',
                'notes' => '',
                'break_start' => null,
                'break_end' => null,
                'break_duration' => 0,
                'approved_by' => null,
                'last_updated' => now()->format('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'employee_id' => 'EMP005',
                'employee_name' => 'Charlie Davis',
                'position' => 'Cleaning Staff',
                'department' => 'Maintenance',
                'date' => date('Y-m-d'),
                'check_in' => null,
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => 0,
                'overtime_hours' => 0,
                'status' => 'not_checked_in',
                'notes' => '',
                'break_start' => null,
                'break_end' => null,
                'break_duration' => 0,
                'approved_by' => null,
                'last_updated' => now()->format('Y-m-d H:i:s')
            ]
        ];
    }

    /**
     * Get attendance data for current user
     */
    public static function getCurrentUserAttendance()
    {
        $user = Session::get('user');
        $employeeId = match($user['role']) {
            'Staff' => 'EMP003',
            'Barista' => 'EMP001',
            'Kasir' => 'EMP002',
            'Manager' => 'EMP999', // Manager tidak perlu absensi
            default => 'EMP001'
        };

        $allAttendances = self::getSharedAttendanceData();
        $userAttendance = collect($allAttendances)->firstWhere('employee_id', $employeeId);

        if ($userAttendance) {
            return $userAttendance;
        }

        // Default attendance record untuk user yang login
        return [
            'employee_id' => $employeeId,
            'employee_name' => $user['name'],
            'position' => $user['role'],
            'department' => match($user['role']) {
                'Staff' => 'Management',
                'Manager' => 'Management',
                'Barista' => 'Production',
                'Kasir' => 'Front Office',
                default => 'Other'
            },
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
    }

    /**
     * Update attendance record (simulasi update ke database dengan sinkronisasi real-time)
     */
    public static function updateAttendanceRecord($employeeId, $data)
    {
        // Update data utama di session untuk sinkronisasi real-time
        $currentData = Session::get('shared_attendance_data', self::getSharedAttendanceData());

        // Find and update the specific employee record
        for ($i = 0; $i < count($currentData); $i++) {
            if ($currentData[$i]['employee_id'] === $employeeId) {
                // Merge the new data with existing data
                $currentData[$i] = array_merge($currentData[$i], $data);
                $currentData[$i]['last_updated'] = now()->format('Y-m-d H:i:s');
                break;
            }
        }

        // Save updated data back to session
        Session::put('shared_attendance_data', $currentData);

        // Also store individual update for tracking
        Session::put("attendance_update_{$employeeId}", array_merge([
            'employee_id' => $employeeId,
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ], $data));

        return true;
    }

    /**
     * Get shared attendance data with real-time updates
     */
    public static function getSharedAttendanceDataRealTime()
    {
        // Check if we have updated data in session
        $sessionData = Session::get('shared_attendance_data');

        if ($sessionData) {
            return $sessionData;
        }

        // Return default data if no session data
        return self::getSharedAttendanceData();
    }

    /**
     * Get historical attendance data untuk laporan
     */
    public static function getHistoricalAttendance($employeeId = null, $dateFrom = null, $dateTo = null)
    {
        // Data dummy riwayat absensi
        $historicalData = [
            [
                'employee_id' => 'EMP001',
                'employee_name' => 'John Doe',
                'date' => date('Y-m-d', strtotime('-4 days')),
                'check_in' => '08:00:00',
                'check_out' => '17:00:00',
                'working_hours' => 9.0,
                'late_minutes' => 0,
                'overtime_hours' => 1.0,
                'status' => 'present'
            ],
            [
                'employee_id' => 'EMP002',
                'employee_name' => 'Jane Smith',
                'date' => date('Y-m-d', strtotime('-4 days')),
                'check_in' => '08:15:00',
                'check_out' => '16:45:00',
                'working_hours' => 8.5,
                'late_minutes' => 15,
                'overtime_hours' => 0,
                'status' => 'present'
            ],
            [
                'employee_id' => 'EMP003',
                'employee_name' => 'Staff User',
                'date' => date('Y-m-d', strtotime('-4 days')),
                'check_in' => '08:00:00',
                'check_out' => '17:00:00',
                'working_hours' => 9.0,
                'late_minutes' => 0,
                'overtime_hours' => 1.0,
                'status' => 'present'
            ],
            [
                'employee_id' => 'EMP003',
                'employee_name' => 'Staff User',
                'date' => date('Y-m-d', strtotime('-3 days')),
                'check_in' => '08:10:00',
                'check_out' => '16:45:00',
                'working_hours' => 8.58,
                'late_minutes' => 10,
                'overtime_hours' => 0,
                'status' => 'present'
            ],
            [
                'employee_id' => 'EMP003',
                'employee_name' => 'Staff User',
                'date' => date('Y-m-d', strtotime('-2 days')),
                'check_in' => null,
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => 0,
                'overtime_hours' => 0,
                'status' => 'sick_leave'
            ],
            [
                'employee_id' => 'EMP003',
                'employee_name' => 'Staff User',
                'date' => date('Y-m-d', strtotime('-1 days')),
                'check_in' => '07:55:00',
                'check_out' => '17:30:00',
                'working_hours' => 9.58,
                'late_minutes' => 0,
                'overtime_hours' => 1.58,
                'status' => 'present'
            ]
        ];

        if ($employeeId) {
            return collect($historicalData)->where('employee_id', $employeeId)->values()->toArray();
        }

        return $historicalData;
    }

    /**
     * Get attendance statistics
     */
    public static function getAttendanceStatistics()
    {
        $attendances = self::getSharedAttendanceData();

        return [
            'total_employees' => count($attendances),
            'present_today' => collect($attendances)->where('status', 'checked_in')->count(),
            'absent_today' => collect($attendances)->where('status', 'not_checked_in')->count(),
            'on_leave_today' => collect($attendances)->where('status', 'on_leave')->count(),
            'late_today' => collect($attendances)->where('late_minutes', '>', 0)->count(),
            'total_overtime_hours' => collect($attendances)->sum('overtime_hours'),
            'team_present' => collect($attendances)->where('status', 'checked_in')->count(),
            'team_total' => count($attendances)
        ];
    }

    /**
     * Get leave requests (simulasi data cuti)
     */
    public static function getLeaveRequests($status = 'all')
    {
        $leaveRequests = [
            [
                'id' => 1,
                'employee_id' => 'EMP003',
                'employee_name' => 'Staff User',
                'leave_type' => 'sick',
                'start_date' => date('Y-m-d', strtotime('+1 day')),
                'end_date' => date('Y-m-d', strtotime('+2 days')),
                'reason' => 'Demam dan flu',
                'status' => 'pending',
                'requested_at' => now()->format('Y-m-d H:i:s'),
                'approved_by' => null,
                'approved_at' => null
            ],
            [
                'id' => 2,
                'employee_id' => 'EMP001',
                'employee_name' => 'John Doe',
                'leave_type' => 'annual',
                'start_date' => date('Y-m-d', strtotime('+7 days')),
                'end_date' => date('Y-m-d', strtotime('+9 days')),
                'reason' => 'Liburan keluarga',
                'status' => 'approved',
                'requested_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'approved_by' => 'Manager',
                'approved_at' => date('Y-m-d H:i:s', strtotime('-6 hours'))
            ]
        ];

        if ($status !== 'all') {
            return collect($leaveRequests)->where('status', $status)->values()->toArray();
        }

        return $leaveRequests;
    }
}
