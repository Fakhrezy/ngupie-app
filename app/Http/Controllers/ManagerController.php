<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ManagerController extends Controller
{
    public function index()
    {
        // Data dummy absensi karyawan
        $attendances = [
            [
                'id' => 1,
                'employee_id' => 'EMP001',
                'employee_name' => 'John Doe',
                'position' => 'Barista',
                'date' => '2025-06-29',
                'check_in' => '08:00:00',
                'check_out' => '17:00:00',
                'working_hours' => 9.0,
                'late_minutes' => 0,
                'overtime_hours' => 1.0,
                'status' => 'present',
                'notes' => '',
                'approved_by' => null
            ],
            [
                'id' => 2,
                'employee_id' => 'EMP002',
                'employee_name' => 'Jane Smith',
                'position' => 'Kasir',
                'date' => '2025-06-29',
                'check_in' => '08:15:00',
                'check_out' => '16:45:00',
                'working_hours' => 8.5,
                'late_minutes' => 15,
                'overtime_hours' => 0,
                'status' => 'present',
                'notes' => 'Terlambat karena macet',
                'approved_by' => null
            ],
            [
                'id' => 3,
                'employee_id' => 'EMP003',
                'employee_name' => 'Bob Wilson',
                'position' => 'Kitchen Staff',
                'date' => '2025-06-29',
                'check_in' => null,
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => 0,
                'overtime_hours' => 0,
                'status' => 'absent',
                'notes' => 'Sakit',
                'approved_by' => null
            ],
            [
                'id' => 4,
                'employee_id' => 'EMP004',
                'employee_name' => 'Alice Brown',
                'position' => 'Barista',
                'date' => '2025-06-29',
                'check_in' => '07:45:00',
                'check_out' => '18:30:00',
                'working_hours' => 10.75,
                'late_minutes' => 0,
                'overtime_hours' => 2.75,
                'status' => 'present',
                'notes' => 'Lembur untuk event khusus',
                'approved_by' => null
            ],
            [
                'id' => 5,
                'employee_id' => 'EMP005',
                'employee_name' => 'Charlie Davis',
                'position' => 'Cleaning Staff',
                'date' => '2025-06-29',
                'check_in' => '09:30:00',
                'check_out' => null,
                'working_hours' => 0,
                'late_minutes' => 90,
                'overtime_hours' => 0,
                'status' => 'late',
                'notes' => 'Terlambat sangat ekstrim',
                'approved_by' => null
            ],
            [
                'id' => 6,
                'employee_id' => 'EMP001',
                'employee_name' => 'John Doe',
                'position' => 'Barista',
                'date' => '2025-06-28',
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
                'id' => 7,
                'employee_id' => 'EMP002',
                'employee_name' => 'Jane Smith',
                'position' => 'Kasir',
                'date' => '2025-06-28',
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

        // Urutkan berdasarkan tanggal terbaru dan status yang perlu perhatian
        usort($attendances, function($a, $b) {
            if ($a['date'] !== $b['date']) {
                return strcmp($b['date'], $a['date']); // Tanggal terbaru dulu
            }

            // Prioritas status yang perlu perhatian
            $statusPriority = [
                'late' => 1,
                'absent' => 2,
                'present' => 3,
                'sick_leave' => 4,
                'annual_leave' => 5
            ];

            return ($statusPriority[$a['status']] ?? 99) - ($statusPriority[$b['status']] ?? 99);
        });

        return view('manager.index', compact('attendances'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|integer',
            'status' => 'required|in:present,absent,late,sick_leave,annual_leave',
            'notes' => 'nullable|string|max:255'
        ]);

        $user = Session::get('user');

        // Simulasi update status absensi
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

        // Simulasi bulk update
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

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil diekspor',
            'file_url' => '/storage/exports/attendance_' . date('Y-m-d_H-i-s') . '.xlsx',
            'date_range' => $dateFrom . ' to ' . $dateTo
        ]);
    }
}
