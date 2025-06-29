@extends('layouts.app')

@section('title', 'Data Karyawan - Coffee Shop Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-user-tie me-2"></i>Data Karyawan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download"></i> Export
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
        <button type="button" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Karyawan
        </button>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h3 mb-0">{{ count($employees) }}</div>
                        <div>Total Karyawan</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-tie fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h3 mb-0">{{ count(array_filter($employees, function($emp) { return $emp['department'] === 'Kitchen'; })) }}</div>
                        <div>Bagian Kitchen</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-coffee fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h3 mb-0">{{ count(array_filter($employees, function($emp) { return $emp['department'] === 'Service'; })) }}</div>
                        <div>Bagian Service</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-concierge-bell fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h6 mb-0">Rp {{ number_format(array_sum(array_column($employees, 'salary')) / count($employees)) }}</div>
                        <div>Rata-rata Gaji</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Karyawan</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Posisi</th>
                        <th>Departemen</th>
                        <th>Gaji</th>
                        <th>Tanggal Masuk</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    <tr>
                        <td>#{{ str_pad($employee['id'], 3, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-success text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    {{ strtoupper(substr($employee['name'], 0, 1)) }}
                                </div>
                                <div>{{ $employee['name'] }}</div>
                            </div>
                        </td>
                        <td>{{ $employee['position'] }}</td>
                        <td>
                            @if($employee['department'] === 'Kitchen')
                                <span class="badge bg-info">{{ $employee['department'] }}</span>
                            @elseif($employee['department'] === 'Service')
                                <span class="badge bg-warning">{{ $employee['department'] }}</span>
                            @else
                                <span class="badge bg-primary">{{ $employee['department'] }}</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($employee['salary']) }}</td>
                        <td>{{ $employee['join_date'] }}</td>
                        <td>
                            <span class="badge bg-success">{{ $employee['status'] }}</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted">Menampilkan {{ count($employees) }} dari {{ count($employees) }} data</span>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
