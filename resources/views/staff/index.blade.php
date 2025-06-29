<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Attendance - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .staff-header {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: none;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .employee-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }
        .employee-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        .status-badge {
            border-radius: 20px;
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            font-weight: 600;
        }
        .status-present { background: #d4edda; color: #155724; }
        .status-absent { background: #f8d7da; color: #721c24; }
        .status-on_leave { background: #fff3cd; color: #856404; }
        .status-sick { background: #e2e3e5; color: #383d41; }

        .time-display {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #8B4513;
        }
        .attendance-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .btn-checkin {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
        }
        .btn-checkout {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
        }
        .btn-status {
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
        }
        .quick-checkin {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .clock-display {
            font-size: 3rem;
            font-weight: bold;
            color: #8B4513;
            text-align: center;
            margin-bottom: 1rem;
            font-family: 'Courier New', monospace;
        }
        .date-display {
            font-size: 1.2rem;
            color: #6c757d;
            text-align: center;
            margin-bottom: 2rem;
        }
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .department-badge {
            background: #e9ecef;
            color: #495057;
            border-radius: 15px;
            font-size: 0.7rem;
            padding: 0.2rem 0.6rem;
        }
        .late-indicator {
            color: #dc3545;
            font-weight: bold;
        }
        .overtime-indicator {
            color: #17a2b8;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="staff-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        Coffee Shop - Staff Attendance
                    </h3>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-flex align-items-center justify-content-end">
                        @if(Session::has('user'))
                        <div class="me-3">
                            <i class="fas fa-user-circle me-2"></i>
                            <span>{{ Session::get('user')['name'] }}</span>
                            <small class="opacity-75 ms-2">({{ Session::get('user')['role'] }})</small>
                        </div>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm"
                                    onclick="return confirm('Logout dari sistem staff?')">
                                <i class="fas fa-sign-out-alt me-1"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid mt-3">
        <!-- Quick Check-in Section -->
        <div class="quick-checkin">
            <div class="row">
                <div class="col-md-6">
                    <div class="clock-display" id="current-time"></div>
                    <div class="date-display" id="current-date"></div>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3">
                        <i class="fas fa-clock me-2"></i>
                        Quick Check-in/out
                    </h5>
                    <div class="mb-3">
                        <label class="form-label">Employee ID</label>
                        <input type="text" class="form-control" id="quick-employee-id" placeholder="Masukkan ID karyawan">
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-checkin flex-fill" onclick="quickCheckIn()">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Check In
                        </button>
                        <button class="btn btn-checkout flex-fill" onclick="quickCheckOut()">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Check Out
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card stat-card">
                    <div class="stat-value text-primary">{{ $monthlyStats['total_employees'] }}</div>
                    <div class="stat-label">Total Karyawan</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stat-card">
                    <div class="stat-value text-success">{{ $monthlyStats['present_today'] }}</div>
                    <div class="stat-label">Hadir Hari Ini</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stat-card">
                    <div class="stat-value text-danger">{{ $monthlyStats['absent_today'] }}</div>
                    <div class="stat-label">Tidak Hadir</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stat-card">
                    <div class="stat-value text-warning">{{ $monthlyStats['on_leave_today'] }}</div>
                    <div class="stat-label">Cuti/Izin</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stat-card">
                    <div class="stat-value text-info">{{ $monthlyStats['late_today'] }}</div>
                    <div class="stat-label">Terlambat</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stat-card">
                    <div class="stat-value text-secondary">{{ $monthlyStats['total_overtime_hours'] }}h</div>
                    <div class="stat-label">Total Lembur</div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Filter Status</label>
                    <select class="form-select" id="status-filter" onchange="filterEmployees()">
                        <option value="all">Semua Status</option>
                        <option value="present">Hadir</option>
                        <option value="absent">Tidak Hadir</option>
                        <option value="on_leave">Cuti/Izin</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter Departemen</label>
                    <select class="form-select" id="department-filter" onchange="filterEmployees()">
                        <option value="all">Semua Departemen</option>
                        <option value="Production">Production</option>
                        <option value="Front Office">Front Office</option>
                        <option value="Kitchen">Kitchen</option>
                        <option value="Management">Management</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cari Karyawan</label>
                    <input type="text" class="form-control" id="search-employee" placeholder="Nama atau ID karyawan" onkeyup="filterEmployees()">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Aksi</label>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary flex-fill" onclick="generateReport()">
                            <i class="fas fa-chart-bar me-1"></i>
                            Laporan
                        </button>
                        <button class="btn btn-success" onclick="exportData()">
                            <i class="fas fa-download me-1"></i>
                            Export
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee List -->
        <div class="row" id="employee-list">
            @foreach($employees as $employee)
            <div class="col-md-6 col-lg-4 employee-item"
                 data-status="{{ $employee['status'] }}"
                 data-department="{{ $employee['department'] }}"
                 data-name="{{ strtolower($employee['name']) }}"
                 data-id="{{ strtolower($employee['employee_id']) }}">
                <div class="card employee-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="card-title mb-1">{{ $employee['name'] }}</h6>
                                <p class="text-muted small mb-1">{{ $employee['employee_id'] }}</p>
                                <p class="text-muted small mb-0">{{ $employee['position'] }}</p>
                            </div>
                            <div class="text-end">
                                <span class="status-badge status-{{ $employee['status'] }}">
                                    @if($employee['status'] === 'present') Hadir
                                    @elseif($employee['status'] === 'absent') Tidak Hadir
                                    @elseif($employee['status'] === 'on_leave') Cuti/Izin
                                    @else Sakit
                                    @endif
                                </span>
                                <br>
                                <span class="department-badge mt-1">{{ $employee['department'] }}</span>
                            </div>
                        </div>

                        <!-- Time Information -->
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Check In</small>
                                <span class="time-display">
                                    {{ $employee['check_in'] ?? '--:--' }}
                                </span>
                                @if($employee['late_minutes'] > 0)
                                <small class="late-indicator d-block">
                                    +{{ $employee['late_minutes'] }} min
                                </small>
                                @endif
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Check Out</small>
                                <span class="time-display">
                                    {{ $employee['check_out'] ?? '--:--' }}
                                </span>
                                @if($employee['overtime_hours'] > 0)
                                <small class="overtime-indicator d-block">
                                    +{{ $employee['overtime_hours'] }}h
                                </small>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="attendance-actions">
                            <button class="btn btn-checkin btn-sm flex-fill"
                                    onclick="checkInEmployee('{{ $employee['employee_id'] }}')"
                                    {{ $employee['status'] === 'present' && $employee['check_in'] ? 'disabled' : '' }}>
                                <i class="fas fa-sign-in-alt me-1"></i>
                                Check In
                            </button>
                            <button class="btn btn-checkout btn-sm flex-fill"
                                    onclick="checkOutEmployee('{{ $employee['employee_id'] }}')"
                                    {{ $employee['status'] !== 'present' || $employee['check_out'] ? 'disabled' : '' }}>
                                <i class="fas fa-sign-out-alt me-1"></i>
                                Check Out
                            </button>
                            <button class="btn btn-status btn-sm"
                                    onclick="updateEmployeeStatus('{{ $employee['employee_id'] }}', '{{ $employee['name'] }}')">
                                <i class="fas fa-edit me-1"></i>
                                Status
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>
                        Update Status Karyawan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Karyawan</label>
                        <input type="text" class="form-control" id="modal-employee-name" readonly>
                        <input type="hidden" id="modal-employee-id">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="modal-status">
                            <option value="present">Hadir</option>
                            <option value="absent">Tidak Hadir</option>
                            <option value="on_leave">Cuti/Izin</option>
                            <option value="sick">Sakit</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan (Opsional)</label>
                        <textarea class="form-control" id="modal-notes" rows="3" placeholder="Tambahkan keterangan jika diperlukan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveStatusUpdate()">
                        <i class="fas fa-save me-1"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-chart-bar me-2"></i>
                        Laporan Absensi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="report-content">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat laporan...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="printReport()">
                        <i class="fas fa-print me-1"></i>
                        Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Update clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID');
            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            document.getElementById('current-time').textContent = timeString;
            document.getElementById('current-date').textContent = dateString;
        }

        // Quick check-in
        async function quickCheckIn() {
            const employeeId = document.getElementById('quick-employee-id').value.trim();
            if (!employeeId) {
                alert('Mohon masukkan Employee ID');
                return;
            }

            try {
                const response = await fetch('{{ route("staff.checkin") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        employee_id: employeeId
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert(`Check-in berhasil!\nWaktu: ${result.data.check_in_time}\n${result.data.late_minutes > 0 ? 'Terlambat: ' + result.data.late_minutes + ' menit' : 'Tepat waktu'}`);
                    document.getElementById('quick-employee-id').value = '';
                    location.reload();
                } else {
                    alert('Gagal melakukan check-in');
                }
            } catch (error) {
                alert('Terjadi kesalahan saat check-in');
            }
        }

        // Quick check-out
        async function quickCheckOut() {
            const employeeId = document.getElementById('quick-employee-id').value.trim();
            if (!employeeId) {
                alert('Mohon masukkan Employee ID');
                return;
            }

            try {
                const response = await fetch('{{ route("staff.checkout") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        employee_id: employeeId
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert(`Check-out berhasil!\nWaktu: ${result.data.check_out_time}\n${result.data.overtime_hours > 0 ? 'Lembur: ' + result.data.overtime_hours + ' jam' : 'Sesuai jam kerja'}`);
                    document.getElementById('quick-employee-id').value = '';
                    location.reload();
                } else {
                    alert('Gagal melakukan check-out');
                }
            } catch (error) {
                alert('Terjadi kesalahan saat check-out');
            }
        }

        // Individual check-in
        async function checkInEmployee(employeeId) {
            try {
                const response = await fetch('{{ route("staff.checkin") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        employee_id: employeeId
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert(`Check-in berhasil untuk ${employeeId}!`);
                    location.reload();
                }
            } catch (error) {
                alert('Terjadi kesalahan saat check-in');
            }
        }

        // Individual check-out
        async function checkOutEmployee(employeeId) {
            try {
                const response = await fetch('{{ route("staff.checkout") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        employee_id: employeeId
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert(`Check-out berhasil untuk ${employeeId}!`);
                    location.reload();
                }
            } catch (error) {
                alert('Terjadi kesalahan saat check-out');
            }
        }

        // Update employee status
        function updateEmployeeStatus(employeeId, employeeName) {
            document.getElementById('modal-employee-id').value = employeeId;
            document.getElementById('modal-employee-name').value = employeeName;

            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        }

        // Save status update
        async function saveStatusUpdate() {
            const employeeId = document.getElementById('modal-employee-id').value;
            const status = document.getElementById('modal-status').value;

            try {
                const response = await fetch('{{ route("staff.update-status") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        employee_id: employeeId,
                        status: status
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert('Status berhasil diperbarui!');
                    bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
                    location.reload();
                }
            } catch (error) {
                alert('Terjadi kesalahan saat memperbarui status');
            }
        }

        // Filter employees
        function filterEmployees() {
            const statusFilter = document.getElementById('status-filter').value;
            const departmentFilter = document.getElementById('department-filter').value;
            const searchTerm = document.getElementById('search-employee').value.toLowerCase();

            const employees = document.querySelectorAll('.employee-item');

            employees.forEach(employee => {
                const status = employee.dataset.status;
                const department = employee.dataset.department;
                const name = employee.dataset.name;
                const id = employee.dataset.id;

                let show = true;

                if (statusFilter !== 'all' && status !== statusFilter) show = false;
                if (departmentFilter !== 'all' && department !== departmentFilter) show = false;
                if (searchTerm && !name.includes(searchTerm) && !id.includes(searchTerm)) show = false;

                employee.style.display = show ? 'block' : 'none';
            });
        }

        // Generate report
        async function generateReport() {
            const modal = new bootstrap.Modal(document.getElementById('reportModal'));
            const content = document.getElementById('report-content');

            content.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat laporan...</p>
                </div>
            `;

            modal.show();

            try {
                const response = await fetch('{{ route("staff.report") }}');
                const result = await response.json();

                if (result.success) {
                    const report = result.report;
                    content.innerHTML = `
                        <div class="mb-4">
                            <h6>Periode: ${report.period}</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Total Hari Kerja:</span>
                                            <strong>${report.summary.total_working_days}</strong>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Total Kehadiran:</span>
                                            <strong>${report.summary.total_present}</strong>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Total Ketidakhadiran:</span>
                                            <strong>${report.summary.total_absent}</strong>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Total Keterlambatan:</span>
                                            <strong>${report.summary.total_late}</strong>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Total Jam Lembur:</span>
                                            <strong>${report.summary.total_overtime_hours}h</strong>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <h6>Detail per Karyawan:</h6>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Hadir</th>
                                        <th>Tidak Hadir</th>
                                        <th>Terlambat</th>
                                        <th>Lembur (jam)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${report.details.map(detail => `
                                        <tr>
                                            <td>${detail.employee_id}</td>
                                            <td>${detail.name}</td>
                                            <td>${detail.present_days}</td>
                                            <td>${detail.absent_days}</td>
                                            <td>${detail.late_count}</td>
                                            <td>${detail.overtime_hours}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `;
                }
            } catch (error) {
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Terjadi kesalahan saat memuat laporan.
                    </div>
                `;
            }
        }

        // Export data
        function exportData() {
            alert('Fitur export akan dikembangkan selanjutnya');
        }

        // Print report
        function printReport() {
            const content = document.getElementById('report-content').innerHTML;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Laporan Absensi</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; }
                            table { width: 100%; border-collapse: collapse; }
                            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                            th { background-color: #f2f2f2; }
                            .list-group-item { display: flex; justify-content: space-between; padding: 8px; border-bottom: 1px solid #ddd; }
                        </style>
                    </head>
                    <body>
                        <h1>Laporan Absensi Coffee Shop</h1>
                        ${content}
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        // Initialize
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>
