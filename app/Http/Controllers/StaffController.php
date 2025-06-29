<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Services\AttendanceDataService;

class StaffController extends Controller
{
    public function index()
    {
        try {
            $user = Session::get('user');

            // Debug: cek apakah user ada
            if (!$user) {
                return redirect()->route('login')->with('error', 'Session tidak ditemukan. Silakan login kembali.');
            }

            // Try to use AttendanceDataService first, fallback to dummy data if error
            try {
                // Get real data from service with real-time updates
                $myAttendance = AttendanceDataService::getCurrentUserAttendance();
                $teamAttendances = AttendanceDataService::getSharedAttendanceDataRealTime();
                $weeklyHistory = AttendanceDataService::getHistoricalAttendance($myAttendance['employee_id']);

                Log::info('Successfully loaded real-time data from AttendanceDataService for staff: ' . $user['name']);

            } catch (\Exception $serviceError) {
                Log::error('AttendanceDataService error, using fallback data: ' . $serviceError->getMessage());

                // Fallback to dummy data
                $myAttendance = [
                    'employee_id' => 'EMP003',
                    'employee_name' => $user['name'],
                    'position' => $user['role'],
                    'department' => 'Management',
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

                $teamAttendances = [
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
                        'notes' => ''
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
                        'notes' => 'Terlambat karena macet'
                    ]
                ];

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
                    ]
                ];
            }

            // Calculate statistics
            $teamAttendanceCollection = collect($teamAttendances);
            $weeklyHistoryCollection = collect($weeklyHistory);

            $stats = [
                'total_employees' => $teamAttendanceCollection->count(),
                'present_today' => $teamAttendanceCollection->where('status', 'checked_in')->count(),
                'absent_today' => $teamAttendanceCollection->where('status', 'absent')->count(),
                'on_leave_today' => $teamAttendanceCollection->where('status', 'on_leave')->count(),
                'late_today' => $teamAttendanceCollection->where('late_minutes', '>', 0)->count(),
                'total_overtime_hours' => $teamAttendanceCollection->sum('overtime_hours'),
                'team_present' => $teamAttendanceCollection->where('status', 'checked_in')->count(),
                'team_total' => $teamAttendanceCollection->count(),
                'my_hours_today' => $myAttendance['working_hours'] ?? 0,
                'my_hours_week' => $weeklyHistoryCollection->sum('working_hours'),
                'my_late_count_week' => $weeklyHistoryCollection->where('late_minutes', '>', 0)->count(),
                'my_overtime_week' => $weeklyHistoryCollection->sum('overtime_hours')
            ];

            // Monthly stats for the view
            $monthlyStats = [
                'total_employees' => $teamAttendanceCollection->count(),
                'present_today' => $teamAttendanceCollection->where('status', 'checked_in')->count(),
                'absent_today' => $teamAttendanceCollection->where('status', 'not_checked_in')->count(),
                'on_leave_today' => $teamAttendanceCollection->whereIn('status', ['sick_leave', 'annual_leave'])->count(),
                'late_today' => $teamAttendanceCollection->where('late_minutes', '>', 0)->count(),
                'total_overtime_hours' => $teamAttendanceCollection->sum('overtime_hours'),
                'monthly_total_hours' => $weeklyHistoryCollection->sum('working_hours') * 4, // Approximate monthly
                'monthly_avg_hours' => round($weeklyHistoryCollection->avg('working_hours') ?? 0, 1),
                'monthly_late_count' => $weeklyHistoryCollection->where('late_minutes', '>', 0)->count() * 4, // Approximate monthly
                'monthly_overtime' => $weeklyHistoryCollection->sum('overtime_hours') * 4 // Approximate monthly
            ];

            // Format employees data for the employee list section
            $employees = [];
            foreach ($teamAttendances as $attendance) {
                $employees[] = [
                    'employee_id' => $attendance['employee_id'],
                    'name' => $attendance['employee_name'],
                    'position' => $attendance['position'],
                    'department' => $attendance['department'] ?? 'Unknown',
                    'status' => $attendance['status'] === 'checked_in' ? 'present' :
                               ($attendance['status'] === 'not_checked_in' ? 'absent' : $attendance['status']),
                    'check_in' => $attendance['check_in'],
                    'check_out' => $attendance['check_out'],
                    'working_hours' => $attendance['working_hours'],
                    'late_minutes' => $attendance['late_minutes'],
                    'overtime_hours' => $attendance['overtime_hours'],
                    'notes' => $attendance['notes'] ?? ''
                ];
            }

            return view('staff.index', compact('myAttendance', 'teamAttendances', 'weeklyHistory', 'stats', 'monthlyStats', 'employees'));

        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('Staff index error: ' . $e->getMessage());

            // Redirect dengan error message
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat memuat halaman staff. Error: ' . $e->getMessage());
        }
    }

    public function checkIn(Request $request)
    {
        try {
            $request->validate([
                'notes' => 'nullable|string|max:255'
            ]);

            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session tidak ditemukan. Silakan login kembali.'
                ], 401);
            }

            $currentTime = now()->format('H:i:s');
            $standardTime = '08:00:00';

            // Hitung keterlambatan
            $lateMinutes = 0;
            if ($currentTime > $standardTime) {
                $lateMinutes = round((strtotime($currentTime) - strtotime($standardTime)) / 60);
            }

            // Update attendance via service
            try {
                $myAttendance = AttendanceDataService::getCurrentUserAttendance();
                AttendanceDataService::updateAttendanceRecord($myAttendance['employee_id'], [
                    'check_in' => $currentTime,
                    'late_minutes' => $lateMinutes,
                    'status' => 'checked_in',
                    'notes' => $request->notes
                ]);

                Log::info('Check-in successful for user: ' . $user['name'] . ' at ' . $currentTime);

                return response()->json([
                    'success' => true,
                    'message' => 'Check-in berhasil!',
                    'data' => [
                        'employee_name' => $user['name'],
                        'check_in_time' => $currentTime,
                        'late_minutes' => $lateMinutes,
                        'date' => date('Y-m-d'),
                        'notes' => $request->notes,
                        'status' => $lateMinutes > 0 ? 'Terlambat ' . $lateMinutes . ' menit' : 'Tepat waktu'
                    ]
                ]);

            } catch (\Exception $serviceError) {
                Log::error('Check-in service error: ' . $serviceError->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat check-in. Silakan coba lagi.',
                    'error' => 'Service error: ' . $serviceError->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Check-in error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat check-in. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkOut(Request $request)
    {
        try {
            $request->validate([
                'notes' => 'nullable|string|max:255'
            ]);

            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session tidak ditemukan. Silakan login kembali.'
                ], 401);
            }

            $currentTime = now()->format('H:i:s');

            try {
                // Get current attendance data
                $myAttendance = AttendanceDataService::getCurrentUserAttendance();

                if (!$myAttendance['check_in']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda belum check-in hari ini. Silakan check-in terlebih dahulu.'
                    ], 400);
                }

                $checkInTime = $myAttendance['check_in'];

                // Hitung jam kerja
                $workingHours = round((strtotime($currentTime) - strtotime($checkInTime)) / 3600, 2);
                $standardHours = 8;
                $overtimeHours = max(0, $workingHours - $standardHours);

                // Update attendance via service
                AttendanceDataService::updateAttendanceRecord($myAttendance['employee_id'], [
                    'check_out' => $currentTime,
                    'working_hours' => $workingHours,
                    'overtime_hours' => $overtimeHours,
                    'status' => 'checked_out',
                    'notes' => $request->notes
                ]);

                Log::info('Check-out successful for user: ' . $user['name'] . ' at ' . $currentTime);

                return response()->json([
                    'success' => true,
                    'message' => 'Check-out berhasil!',
                    'data' => [
                        'employee_name' => $user['name'],
                        'check_out_time' => $currentTime,
                        'working_hours' => $workingHours,
                        'overtime_hours' => $overtimeHours,
                        'date' => date('Y-m-d'),
                        'notes' => $request->notes,
                        'status' => $overtimeHours > 0 ? 'Lembur ' . $overtimeHours . ' jam' : 'Normal'
                    ]
                ]);

            } catch (\Exception $serviceError) {
                Log::error('Check-out service error: ' . $serviceError->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat check-out. Silakan coba lagi.',
                    'error' => 'Service error: ' . $serviceError->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Check-out error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat check-out. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }
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

        // Simulasi submit request cuti via service
        // Dalam implementasi nyata, ini akan menyimpan ke database
        Session::put('leave_request_' . time(), [
            'employee_name' => $user['name'],
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
            'requested_at' => now()->format('Y-m-d H:i:s')
        ]);

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
        try {
            $request->validate([
                'type' => 'required|in:start,end'
            ]);

            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session tidak ditemukan. Silakan login kembali.'
                ], 401);
            }

            $currentTime = now()->format('H:i:s');

            try {
                $myAttendance = AttendanceDataService::getCurrentUserAttendance();

                if (!$myAttendance['check_in']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda belum check-in hari ini. Silakan check-in terlebih dahulu.'
                    ], 400);
                }

                if ($request->type === 'start') {
                    if ($myAttendance['break_start'] && !$myAttendance['break_end']) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Anda sudah memulai break. Silakan akhiri break terlebih dahulu.'
                        ], 400);
                    }

                    $message = 'Break dimulai';
                    AttendanceDataService::updateAttendanceRecord($myAttendance['employee_id'], [
                        'break_start' => $currentTime
                    ]);

                    $responseData = [
                        'employee_name' => $user['name'],
                        'break_time' => $currentTime,
                        'type' => $request->type,
                        'status' => 'Break dimulai'
                    ];

                } else {
                    if (!$myAttendance['break_start']) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Anda belum memulai break. Silakan mulai break terlebih dahulu.'
                        ], 400);
                    }

                    $message = 'Break selesai';
                    $breakStart = $myAttendance['break_start'] ?? '12:00:00';
                    $breakDuration = round((strtotime($currentTime) - strtotime($breakStart)) / 60);

                    AttendanceDataService::updateAttendanceRecord($myAttendance['employee_id'], [
                        'break_end' => $currentTime,
                        'break_duration' => $breakDuration
                    ]);

                    $responseData = [
                        'employee_name' => $user['name'],
                        'break_time' => $currentTime,
                        'break_duration' => $breakDuration,
                        'type' => $request->type,
                        'status' => 'Break selesai - Durasi: ' . $breakDuration . ' menit'
                    ];
                }

                Log::info('Break action successful for user: ' . $user['name'] . ' - ' . $request->type . ' at ' . $currentTime);

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $responseData
                ]);

            } catch (\Exception $serviceError) {
                Log::error('Break service error: ' . $serviceError->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memproses break. Silakan coba lagi.',
                    'error' => 'Service error: ' . $serviceError->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Break error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses break. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'status' => 'required|in:present,absent,on_leave,sick'
        ]);

        $user = Session::get('user');

        // Simulasi update status karyawan
        return response()->json([
            'success' => true,
            'message' => 'Status karyawan berhasil diperbarui',
            'data' => [
                'employee_id' => $request->employee_id,
                'status' => $request->status,
                'updated_by' => $user['name'],
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]
        ]);
    }

    public function report(Request $request)
    {
        // Simulasi data laporan
        $reportData = [
            'period' => date('Y-m-d', strtotime('-30 days')) . ' - ' . date('Y-m-d'),
            'summary' => [
                'total_working_days' => 22,
                'total_present' => 88,
                'total_absent' => 12,
                'total_late' => 15,
                'total_overtime_hours' => 45.5
            ],
            'details' => [
                [
                    'employee_id' => 'EMP001',
                    'name' => 'John Doe',
                    'present_days' => 20,
                    'absent_days' => 2,
                    'late_count' => 3,
                    'overtime_hours' => 12.5
                ],
                [
                    'employee_id' => 'EMP002',
                    'name' => 'Jane Smith',
                    'present_days' => 22,
                    'absent_days' => 0,
                    'late_count' => 1,
                    'overtime_hours' => 8.0
                ],
                [
                    'employee_id' => 'EMP003',
                    'name' => 'Staff User',
                    'present_days' => 21,
                    'absent_days' => 1,
                    'late_count' => 2,
                    'overtime_hours' => 15.0
                ],
                [
                    'employee_id' => 'EMP004',
                    'name' => 'Alice Brown',
                    'present_days' => 19,
                    'absent_days' => 3,
                    'late_count' => 4,
                    'overtime_hours' => 6.5
                ],
                [
                    'employee_id' => 'EMP005',
                    'name' => 'Charlie Davis',
                    'present_days' => 18,
                    'absent_days' => 4,
                    'late_count' => 5,
                    'overtime_hours' => 3.5
                ]
            ]
        ];

        return response()->json([
            'success' => true,
            'report' => $reportData
        ]);
    }

    /**
     * Get real-time attendance updates for AJAX calls
     */
    public function getAttendanceUpdates()
    {
        try {
            $user = Session::get('user');

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session tidak ditemukan'
                ], 401);
            }

            // Get real-time data
            $attendances = AttendanceDataService::getSharedAttendanceDataRealTime();
            $myAttendance = AttendanceDataService::getCurrentUserAttendance();

            // Calculate fresh statistics
            $teamAttendanceCollection = collect($attendances);
            $stats = [
                'total_employees' => $teamAttendanceCollection->count(),
                'present_today' => $teamAttendanceCollection->where('status', 'checked_in')->count(),
                'absent_today' => $teamAttendanceCollection->where('status', 'not_checked_in')->count(),
                'on_leave_today' => $teamAttendanceCollection->whereIn('status', ['sick_leave', 'annual_leave'])->count(),
                'late_today' => $teamAttendanceCollection->where('late_minutes', '>', 0)->count(),
                'total_overtime_hours' => $teamAttendanceCollection->sum('overtime_hours'),
                'last_updated' => now()->format('Y-m-d H:i:s')
            ];

            // Monthly stats for consistency with the main view
            $monthlyStats = [
                'total_employees' => $teamAttendanceCollection->count(),
                'present_today' => $teamAttendanceCollection->where('status', 'checked_in')->count(),
                'absent_today' => $teamAttendanceCollection->where('status', 'not_checked_in')->count(),
                'on_leave_today' => $teamAttendanceCollection->whereIn('status', ['sick_leave', 'annual_leave'])->count(),
                'late_today' => $teamAttendanceCollection->where('late_minutes', '>', 0)->count(),
                'total_overtime_hours' => $teamAttendanceCollection->sum('overtime_hours'),
                'last_updated' => now()->format('Y-m-d H:i:s')
            ];

            // Format employees data for consistency
            $employees = [];
            foreach ($attendances as $attendance) {
                $employees[] = [
                    'employee_id' => $attendance['employee_id'],
                    'name' => $attendance['employee_name'],
                    'position' => $attendance['position'],
                    'department' => $attendance['department'] ?? 'Unknown',
                    'status' => $attendance['status'] === 'checked_in' ? 'present' :
                               ($attendance['status'] === 'not_checked_in' ? 'absent' : $attendance['status']),
                    'check_in' => $attendance['check_in'],
                    'check_out' => $attendance['check_out'],
                    'working_hours' => $attendance['working_hours'],
                    'late_minutes' => $attendance['late_minutes'],
                    'overtime_hours' => $attendance['overtime_hours'],
                    'notes' => $attendance['notes'] ?? ''
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'my_attendance' => $myAttendance,
                    'team_attendances' => $attendances,
                    'employees' => $employees,
                    'stats' => $stats,
                    'monthlyStats' => $monthlyStats
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get attendance updates error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data update',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
