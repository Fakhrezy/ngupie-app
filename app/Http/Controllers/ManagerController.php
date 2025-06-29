<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Services\AttendanceDataService;

class ManagerController extends Controller
{
    public function index()
    {
        // Get real-time data dari service yang sama dengan staff
        $attendances = AttendanceDataService::getSharedAttendanceDataRealTime();

        // Convert untuk format yang diharapkan manager view
        $formattedAttendances = [];
        foreach ($attendances as $attendance) {
            $formattedAttendances[] = [
                'id' => $attendance['id'],
                'employee_id' => $attendance['employee_id'],
                'employee_name' => $attendance['employee_name'],
                'position' => $attendance['position'],
                'date' => $attendance['date'],
                'check_in' => $attendance['check_in'],
                'check_out' => $attendance['check_out'],
                'working_hours' => $attendance['working_hours'],
                'late_minutes' => $attendance['late_minutes'],
                'overtime_hours' => $attendance['overtime_hours'],
                'status' => $attendance['status'] === 'checked_in' ? 'present' :
                           ($attendance['status'] === 'not_checked_in' ? 'absent' : $attendance['status']),
                'notes' => $attendance['notes'],
                'approved_by' => $attendance['approved_by'] ?? null,
                'last_updated' => $attendance['last_updated'] ?? now()->format('Y-m-d H:i:s')
            ];
        }

        // Tambah beberapa data historis untuk demo
        $historicalData = [
            [
                'id' => 10,
                'employee_id' => 'EMP001',
                'employee_name' => 'John Doe',
                'position' => 'Barista',
                'date' => date('Y-m-d', strtotime('-1 day')),
                'check_in' => '08:00:00',
                'check_out' => '17:00:00',
                'working_hours' => 9.0,
                'late_minutes' => 0,
                'overtime_hours' => 1.0,
                'status' => 'present',
                'notes' => '',
                'approved_by' => 'Manager'
            ],
            [
                'id' => 11,
                'employee_id' => 'EMP002',
                'employee_name' => 'Jane Smith',
                'position' => 'Kasir',
                'date' => date('Y-m-d', strtotime('-1 day')),
                'check_in' => null,
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => 0,
                'overtime_hours' => 0,
                'status' => 'sick_leave',
                'notes' => 'Cuti sakit dengan surat dokter',
                'approved_by' => 'Manager'
            ]
        ];

        $attendances = array_merge($formattedAttendances, $historicalData);

        // Urutkan berdasarkan tanggal terbaru dan status yang perlu perhatian
        usort($attendances, function($a, $b) {
            if ($a['date'] !== $b['date']) {
                return strcmp($b['date'], $a['date']); // Tanggal terbaru dulu
            }

            // Prioritas status yang perlu perhatian
            $statusPriority = [
                'absent' => 1,
                'late' => 2,
                'present' => 3,
                'sick_leave' => 4,
                'annual_leave' => 5
            ];

            return ($statusPriority[$a['status']] ?? 99) - ($statusPriority[$b['status']] ?? 99);
        });

        // Get leave requests
        $leaveRequests = AttendanceDataService::getLeaveRequests();

        // Get statistics
        $stats = AttendanceDataService::getAttendanceStatistics();

        return view('manager.index', compact('attendances', 'leaveRequests', 'stats'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|integer',
            'status' => 'required|in:present,absent,late,sick_leave,annual_leave',
            'notes' => 'nullable|string|max:255'
        ]);

        $user = Session::get('user');

        // Update melalui service
        AttendanceDataService::updateAttendanceRecord('attendance_' . $request->attendance_id, [
            'status' => $request->status,
            'notes' => $request->notes,
            'approved_by' => $user['name'],
            'approved_at' => now()->format('Y-m-d H:i:s')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status absensi berhasil diperbarui',
            'attendance_id' => $request->attendance_id,
            'new_status' => $request->status,
            'notes' => $request->notes,
            'approved_by' => $user['name']
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'attendance_ids' => 'required|array',
            'attendance_ids.*' => 'integer',
            'status' => 'required|in:present,absent,late,sick_leave,annual_leave',
            'notes' => 'nullable|string|max:255'
        ]);

        $user = Session::get('user');

        // Simulasi bulk update melalui service
        foreach ($request->attendance_ids as $attendanceId) {
            AttendanceDataService::updateAttendanceRecord('attendance_' . $attendanceId, [
                'status' => $request->status,
                'notes' => $request->notes,
                'approved_by' => $user['name'],
                'approved_at' => now()->format('Y-m-d H:i:s')
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status absensi berhasil diperbarui untuk ' . count($request->attendance_ids) . ' karyawan',
            'updated_count' => count($request->attendance_ids),
            'approved_by' => $user['name']
        ]);
    }

    public function export(Request $request)
    {
        // Simulasi export data absensi
        $dateFrom = $request->get('date_from', date('Y-m-01'));
        $dateTo = $request->get('date_to', date('Y-m-d'));

        // Get data untuk export
        $attendances = AttendanceDataService::getSharedAttendanceData();
        $historical = AttendanceDataService::getHistoricalAttendance();

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil diekspor',
            'file_url' => '/storage/exports/attendance_' . date('Y-m-d_H-i-s') . '.xlsx',
            'date_range' => $dateFrom . ' to ' . $dateTo,
            'total_records' => count($attendances) + count($historical)
        ]);
    }

    public function approveLeave(Request $request)
    {
        $request->validate([
            'leave_id' => 'required|integer',
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:255'
        ]);

        $user = Session::get('user');

        // Simulasi approve/reject cuti
        return response()->json([
            'success' => true,
            'message' => $request->action === 'approve' ? 'Permohonan cuti disetujui' : 'Permohonan cuti ditolak',
            'leave_id' => $request->leave_id,
            'action' => $request->action,
            'approved_by' => $user['name'],
            'notes' => $request->notes
        ]);
    }

    /**
     * Get real-time attendance updates for manager dashboard
     */
    public function getAttendanceUpdates()
    {
        try {
            $user = Session::get('user');

            if (!$user || $user['role'] !== 'Manager') {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Hanya manager yang dapat mengakses data ini.'
                ], 403);
            }

            // Get real-time data
            $attendances = AttendanceDataService::getSharedAttendanceDataRealTime();
            $leaveRequests = AttendanceDataService::getLeaveRequests();
            $stats = AttendanceDataService::getAttendanceStatistics();

            // Format data untuk manager view
            $formattedAttendances = [];
            foreach ($attendances as $attendance) {
                $formattedAttendances[] = [
                    'id' => $attendance['id'],
                    'employee_id' => $attendance['employee_id'],
                    'employee_name' => $attendance['employee_name'],
                    'position' => $attendance['position'],
                    'date' => $attendance['date'],
                    'check_in' => $attendance['check_in'],
                    'check_out' => $attendance['check_out'],
                    'working_hours' => $attendance['working_hours'],
                    'late_minutes' => $attendance['late_minutes'],
                    'overtime_hours' => $attendance['overtime_hours'],
                    'status' => $attendance['status'] === 'checked_in' ? 'present' :
                               ($attendance['status'] === 'not_checked_in' ? 'absent' : $attendance['status']),
                    'notes' => $attendance['notes'],
                    'last_updated' => $attendance['last_updated'] ?? now()->format('Y-m-d H:i:s')
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'attendances' => $formattedAttendances,
                    'leave_requests' => $leaveRequests,
                    'stats' => $stats,
                    'last_sync' => now()->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Manager get attendance updates error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data update',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
