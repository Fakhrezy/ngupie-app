<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Riwayat Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .manager-header {
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .attendance-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            margin-bottom: 1rem;
        }
        .attendance-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        .attendance-card.status-present {
            border-left: 5px solid #198754;
        }
        .attendance-card.status-absent {
            border-left: 5px solid #dc3545;
        }
        .attendance-card.status-late {
            border-left: 5px solid #fd7e14;
        }
        .attendance-card.status-sick_leave {
            border-left: 5px solid #6f42c1;
        }
        .attendance-card.status-annual_leave {
            border-left: 5px solid #0dcaf0;
        }
        .status-badge {
            border-radius: 20px;
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
        }
        .time-badge {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            margin: 0.25rem;
            display: inline-block;
        }
        .stats-cards {
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: none;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .btn-status {
            border-radius: 20px;
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border: none;
            transition: all 0.3s;
        }
        .btn-present {
            background: #198754;
            color: white;
        }
        .btn-absent {
            background: #dc3545;
            color: white;
        }
        .btn-late {
            background: #fd7e14;
            color: white;
        }
        .btn-sick {
            background: #6f42c1;
            color: white;
        }
        .btn-leave {
            background: #0dcaf0;
            color: white;
        }
        .bulk-actions {
            background: #fff;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: none;
        }
        .bulk-actions.show {
            display: block;
        }
        .checkbox-col {
            width: 40px;
        }
        .employee-info {
            display: flex;
            align-items: center;
        }
        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6f42c1, #e83e8c);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 0.75rem;
        }
        .working-hours {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .notes-section {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }
        .approved-badge {
            background: #e7f3ff;
            color: #0066cc;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="manager-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>
                        Manager - Riwayat Absensi Karyawan
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
                                    onclick="return confirm('Logout dari manager panel?')">
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
        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row stats-cards">
            <div class="col-md-2">
                <div class="card stat-card">
                    <div class="stat-value text-success" id="present-count">
                        {{ collect($attendances)->where('status', 'present')->count() }}
                    </div>
                    <div class="stat-label">Hadir</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stat-card">
                    <div class="stat-value text-danger" id="absent-count">
                        {{ collect($attendances)->where('status', 'absent')->count() }}
                    </div>
                    <div class="stat-label">Tidak Hadir</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stat-card">
                    <div class="stat-value text-warning" id="late-count">
                        {{ collect($attendances)->where('status', 'late')->count() }}
                    </div>
                    <div class="stat-label">Terlambat</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stat-card">
                    <div class="stat-value text-info" id="sick-count">
                        {{ collect($attendances)->where('status', 'sick_leave')->count() }}
                    </div>
                    <div class="stat-label">Sakit</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stat-card">
                    <div class="stat-value text-primary" id="leave-count">
                        {{ collect($attendances)->where('status', 'annual_leave')->count() }}
                    </div>
                    <div class="stat-label">Cuti</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stat-card">
                    <div class="stat-value text-secondary" id="pending-count">
                        {{ collect($attendances)->where('approved_by', null)->count() }}
                    </div>
                    <div class="stat-label">Perlu Approval</div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Filter Status:</label>
                            <select class="form-select" id="statusFilter" onchange="filterAttendances()">
                                <option value="all">Semua Status</option>
                                <option value="present">Hadir</option>
                                <option value="absent">Tidak Hadir</option>
                                <option value="late">Terlambat</option>
                                <option value="sick_leave">Sakit</option>
                                <option value="annual_leave">Cuti</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter Tanggal:</label>
                            <input type="date" class="form-control" id="dateFilter"
                                   value="{{ date('Y-m-d') }}" onchange="filterAttendances()">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter Approval:</label>
                            <select class="form-select" id="approvalFilter" onchange="filterAttendances()">
                                <option value="all">Semua</option>
                                <option value="pending">Perlu Approval</option>
                                <option value="approved">Sudah Disetujui</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cari Karyawan:</label>
                            <input type="text" class="form-control" id="employeeSearch"
                                   placeholder="Nama atau ID karyawan..." oninput="filterAttendances()">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-primary me-2" onclick="toggleBulkMode()">
                        <i class="fas fa-check-square me-1"></i>
                        Mode Bulk
                    </button>
                    <button class="btn btn-success" onclick="exportData()">
                        <i class="fas fa-download me-1"></i>
                        Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulkActions">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <strong>Terpilih: <span id="selectedCount">0</span> karyawan</strong>
                </div>
                <div class="col-md-6 text-end">
                    <select class="form-select d-inline-block me-2" style="width: auto;" id="bulkStatus">
                        <option value="">Pilih Status</option>
                        <option value="present">Hadir</option>
                        <option value="absent">Tidak Hadir</option>
                        <option value="late">Terlambat</option>
                        <option value="sick_leave">Sakit</option>
                        <option value="annual_leave">Cuti</option>
                    </select>
                    <button class="btn btn-primary me-2" onclick="bulkUpdate()">
                        <i class="fas fa-save me-1"></i>
                        Update Terpilih
                    </button>
                    <button class="btn btn-secondary" onclick="toggleBulkMode()">
                        <i class="fas fa-times me-1"></i>
                        Batal
                    </button>
                </div>
            </div>
        </div>

        <!-- Attendance List -->
        <div class="row">
            <div class="col-12">
                <div id="attendances-container">
                    @foreach($attendances as $attendance)
                    <div class="attendance-card card status-{{ $attendance['status'] }}"
                         data-status="{{ $attendance['status'] }}"
                         data-date="{{ $attendance['date'] }}"
                         data-employee="{{ strtolower($attendance['employee_name'] . ' ' . $attendance['employee_id']) }}"
                         data-approval="{{ $attendance['approved_by'] ? 'approved' : 'pending' }}"
                         data-attendance-id="{{ $attendance['id'] }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="checkbox-col d-none">
                                    <input type="checkbox" class="form-check-input bulk-checkbox"
                                           value="{{ $attendance['id'] }}" onchange="updateSelectedCount()">
                                </div>
                                <div class="col-md-4">
                                    <div class="employee-info">
                                        <div class="employee-avatar">
                                            {{ substr($attendance['employee_name'], 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $attendance['employee_name'] }}</h6>
                                            <p class="mb-0 text-muted">
                                                {{ $attendance['employee_id'] }} - {{ $attendance['position'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-2">
                                        <strong>{{ date('d M Y', strtotime($attendance['date'])) }}</strong>
                                    </div>
                                    <div class="time-badges">
                                        @if($attendance['check_in'])
                                            <span class="time-badge">
                                                <i class="fas fa-sign-in-alt text-success me-1"></i>
                                                Masuk: {{ $attendance['check_in'] }}
                                            </span>
                                        @endif
                                        @if($attendance['check_out'])
                                            <span class="time-badge">
                                                <i class="fas fa-sign-out-alt text-danger me-1"></i>
                                                Keluar: {{ $attendance['check_out'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="working-hours mt-1">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $attendance['working_hours'] }} jam kerja
                                        @if($attendance['late_minutes'] > 0)
                                            <span class="text-warning">(Terlambat {{ $attendance['late_minutes'] }} menit)</span>
                                        @endif
                                        @if($attendance['overtime_hours'] > 0)
                                            <span class="text-info">(Lembur {{ $attendance['overtime_hours'] }} jam)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="badge status-badge
                                        @if($attendance['status'] === 'present') bg-success
                                        @elseif($attendance['status'] === 'absent') bg-danger
                                        @elseif($attendance['status'] === 'late') bg-warning text-dark
                                        @elseif($attendance['status'] === 'sick_leave') bg-purple text-white
                                        @else bg-info
                                        @endif">
                                        @if($attendance['status'] === 'present') Hadir
                                        @elseif($attendance['status'] === 'absent') Tidak Hadir
                                        @elseif($attendance['status'] === 'late') Terlambat
                                        @elseif($attendance['status'] === 'sick_leave') Sakit
                                        @else Cuti
                                        @endif
                                    </span>
                                    @if($attendance['approved_by'])
                                        <div class="approved-badge mt-1">
                                            <i class="fas fa-check me-1"></i>
                                            Disetujui
                                        </div>
                                    @else
                                        <div class="text-warning mt-1">
                                            <i class="fas fa-clock me-1"></i>
                                            Perlu Approval
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <div class="action-buttons">
                                        <button class="btn btn-status btn-present btn-sm"
                                                onclick="updateStatus({{ $attendance['id'] }}, 'present')"
                                                {{ $attendance['status'] === 'present' ? 'disabled' : '' }}>
                                            <i class="fas fa-check me-1"></i>
                                            Hadir
                                        </button>
                                        <button class="btn btn-status btn-absent btn-sm"
                                                onclick="updateStatus({{ $attendance['id'] }}, 'absent')"
                                                {{ $attendance['status'] === 'absent' ? 'disabled' : '' }}>
                                            <i class="fas fa-times me-1"></i>
                                            Absent
                                        </button>
                                        <button class="btn btn-status btn-late btn-sm"
                                                onclick="updateStatus({{ $attendance['id'] }}, 'late')"
                                                {{ $attendance['status'] === 'late' ? 'disabled' : '' }}>
                                            <i class="fas fa-exclamation me-1"></i>
                                            Terlambat
                                        </button>
                                        <button class="btn btn-status btn-sick btn-sm"
                                                onclick="updateStatus({{ $attendance['id'] }}, 'sick_leave')"
                                                {{ $attendance['status'] === 'sick_leave' ? 'disabled' : '' }}>
                                            <i class="fas fa-thermometer me-1"></i>
                                            Sakit
                                        </button>
                                        <button class="btn btn-status btn-leave btn-sm"
                                                onclick="updateStatus({{ $attendance['id'] }}, 'annual_leave')"
                                                {{ $attendance['status'] === 'annual_leave' ? 'disabled' : '' }}>
                                            <i class="fas fa-calendar me-1"></i>
                                            Cuti
                                        </button>
                                    </div>
                                    @if($attendance['notes'])
                                        <div class="notes-section">
                                            <strong>Catatan:</strong> {{ $attendance['notes'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="statusForm">
                        <div class="mb-3">
                            <label class="form-label">Status Baru:</label>
                            <select class="form-select" id="modalStatus" required>
                                <option value="present">Hadir</option>
                                <option value="absent">Tidak Hadir</option>
                                <option value="late">Terlambat</option>
                                <option value="sick_leave">Sakit</option>
                                <option value="annual_leave">Cuti</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan (Opsional):</label>
                            <textarea class="form-control" id="modalNotes" rows="3"
                                      placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="confirmStatusUpdate()">
                        <i class="fas fa-save me-1"></i>
                        Update Status
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle me-2"></i>
                        Berhasil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="success-message">Status absensi berhasil diperbarui!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let currentAttendanceId = null;
        let bulkMode = false;

        // Filter attendances
        function filterAttendances() {
            const statusFilter = document.getElementById('statusFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            const approvalFilter = document.getElementById('approvalFilter').value;
            const employeeSearch = document.getElementById('employeeSearch').value.toLowerCase();

            const attendances = document.querySelectorAll('.attendance-card');

            attendances.forEach(attendance => {
                const status = attendance.dataset.status;
                const date = attendance.dataset.date;
                const employee = attendance.dataset.employee;
                const approval = attendance.dataset.approval;

                let show = true;

                // Filter by status
                if (statusFilter !== 'all' && status !== statusFilter) {
                    show = false;
                }

                // Filter by date
                if (dateFilter && date !== dateFilter) {
                    show = false;
                }

                // Filter by approval
                if (approvalFilter !== 'all' && approval !== approvalFilter) {
                    show = false;
                }

                // Filter by employee search
                if (employeeSearch && !employee.includes(employeeSearch)) {
                    show = false;
                }

                attendance.style.display = show ? 'block' : 'none';
            });

            updateStatistics();
        }

        // Update status
        function updateStatus(attendanceId, status) {
            currentAttendanceId = attendanceId;
            document.getElementById('modalStatus').value = status;
            document.getElementById('modalNotes').value = '';

            new bootstrap.Modal(document.getElementById('statusModal')).show();
        }

        // Confirm status update
        async function confirmStatusUpdate() {
            const status = document.getElementById('modalStatus').value;
            const notes = document.getElementById('modalNotes').value;

            try {
                const response = await fetch('{{ route("manager.update-status") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        attendance_id: currentAttendanceId,
                        status: status,
                        notes: notes
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const result = await response.json();

                if (result.success) {
                    // Update UI
                    const attendanceCard = document.querySelector(`[data-attendance-id="${currentAttendanceId}"]`);
                    if (attendanceCard) {
                        // Update card classes
                        attendanceCard.className = attendanceCard.className.replace(/status-\w+/, `status-${status}`);
                        attendanceCard.dataset.status = status;
                        attendanceCard.dataset.approval = 'approved';

                        // Update status badge
                        const badge = attendanceCard.querySelector('.status-badge');
                        badge.className = 'badge status-badge';

                        if (status === 'present') {
                            badge.classList.add('bg-success');
                            badge.textContent = 'Hadir';
                        } else if (status === 'absent') {
                            badge.classList.add('bg-danger');
                            badge.textContent = 'Tidak Hadir';
                        } else if (status === 'late') {
                            badge.classList.add('bg-warning', 'text-dark');
                            badge.textContent = 'Terlambat';
                        } else if (status === 'sick_leave') {
                            badge.classList.add('bg-purple', 'text-white');
                            badge.textContent = 'Sakit';
                        } else {
                            badge.classList.add('bg-info');
                            badge.textContent = 'Cuti';
                        }

                        // Update buttons
                        const buttons = attendanceCard.querySelectorAll('.btn-status');
                        buttons.forEach(btn => btn.disabled = false);

                        const targetBtn = attendanceCard.querySelector(`.btn-${status === 'sick_leave' ? 'sick' : status === 'annual_leave' ? 'leave' : status}`);
                        if (targetBtn) targetBtn.disabled = true;

                        // Update approval status
                        const approvalDiv = attendanceCard.querySelector('.text-warning');
                        if (approvalDiv) {
                            approvalDiv.className = 'approved-badge mt-1';
                            approvalDiv.innerHTML = '<i class="fas fa-check me-1"></i>Disetujui';
                        }

                        // Add/update notes
                        if (notes) {
                            let notesSection = attendanceCard.querySelector('.notes-section');
                            if (!notesSection) {
                                notesSection = document.createElement('div');
                                notesSection.className = 'notes-section';
                                attendanceCard.querySelector('.action-buttons').appendChild(notesSection);
                            }
                            notesSection.innerHTML = `<strong>Catatan:</strong> ${notes}`;
                        }
                    }

                    // Update statistics
                    updateStatistics();

                    // Close modal and show success
                    bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();

                    document.getElementById('success-message').textContent = result.message;
                    new bootstrap.Modal(document.getElementById('successModal')).show();
                } else {
                    throw new Error(result.message || 'Update gagal');
                }
            } catch (error) {
                console.error('Error updating status:', error);
                alert('Terjadi kesalahan saat memperbarui status: ' + error.message);
            }
        }

        // Toggle bulk mode
        function toggleBulkMode() {
            bulkMode = !bulkMode;
            const checkboxCols = document.querySelectorAll('.checkbox-col');
            const bulkActions = document.getElementById('bulkActions');

            if (bulkMode) {
                checkboxCols.forEach(col => col.classList.remove('d-none'));
                bulkActions.classList.add('show');
            } else {
                checkboxCols.forEach(col => col.classList.add('d-none'));
                bulkActions.classList.remove('show');
                // Uncheck all
                document.querySelectorAll('.bulk-checkbox').forEach(cb => cb.checked = false);
                updateSelectedCount();
            }
        }

        // Update selected count
        function updateSelectedCount() {
            const selected = document.querySelectorAll('.bulk-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = selected;
        }

        // Bulk update
        async function bulkUpdate() {
            const selectedIds = Array.from(document.querySelectorAll('.bulk-checkbox:checked')).map(cb => parseInt(cb.value));
            const status = document.getElementById('bulkStatus').value;

            if (selectedIds.length === 0) {
                alert('Pilih setidaknya satu karyawan');
                return;
            }

            if (!status) {
                alert('Pilih status yang akan diupdate');
                return;
            }

            try {
                const response = await fetch('{{ route("manager.bulk-update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        attendance_ids: selectedIds,
                        status: status,
                        notes: 'Bulk update oleh manager'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    // Update UI for all selected items
                    selectedIds.forEach(id => {
                        const attendanceCard = document.querySelector(`[data-attendance-id="${id}"]`);
                        if (attendanceCard) {
                            attendanceCard.className = attendanceCard.className.replace(/status-\w+/, `status-${status}`);
                            attendanceCard.dataset.status = status;
                            attendanceCard.dataset.approval = 'approved';
                        }
                    });

                    toggleBulkMode();
                    updateStatistics();

                    document.getElementById('success-message').textContent = result.message;
                    new bootstrap.Modal(document.getElementById('successModal')).show();
                }
            } catch (error) {
                console.error('Error bulk updating:', error);
                alert('Terjadi kesalahan saat bulk update');
            }
        }

        // Export data
        async function exportData() {
            try {
                const response = await fetch('{{ route("manager.export") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        date_from: document.getElementById('dateFilter').value || '{{ date('Y-m-01') }}',
                        date_to: document.getElementById('dateFilter').value || '{{ date('Y-m-d') }}'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById('success-message').textContent = result.message;
                    new bootstrap.Modal(document.getElementById('successModal')).show();
                }
            } catch (error) {
                console.error('Error exporting:', error);
                alert('Terjadi kesalahan saat export data');
            }
        }

        // Update statistics
        function updateStatistics() {
            const visibleCards = Array.from(document.querySelectorAll('.attendance-card')).filter(card =>
                card.style.display !== 'none'
            );

            const stats = {
                present: 0,
                absent: 0,
                late: 0,
                sick_leave: 0,
                annual_leave: 0,
                pending: 0
            };

            visibleCards.forEach(card => {
                const status = card.dataset.status;
                const approval = card.dataset.approval;

                stats[status]++;
                if (approval === 'pending') stats.pending++;
            });

            document.getElementById('present-count').textContent = stats.present;
            document.getElementById('absent-count').textContent = stats.absent;
            document.getElementById('late-count').textContent = stats.late;
            document.getElementById('sick-count').textContent = stats.sick_leave;
            document.getElementById('leave-count').textContent = stats.annual_leave;
            document.getElementById('pending-count').textContent = stats.pending;
        }

        // Auto refresh every 2 minutes
        setInterval(() => {
            if (!bulkMode) {
                location.reload();
            }
        }, 120000);
    </script>
</body>
</html>
