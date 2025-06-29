@extends('layouts.app')

@section('title', 'Data Pengguna - Coffee Shop Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-users me-2"></i>Data Pengguna</h1>
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
            <i class="fas fa-plus"></i> Tambah Pengguna
        </button>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h3 mb-0">{{ count($users) }}</div>
                        <div>Total Pengguna</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h3 mb-0">{{ count(array_filter($users, function($user) { return $user['status'] === 'Active'; })) }}</div>
                        <div>Pengguna Aktif</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-check fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h3 mb-0">{{ count(array_filter($users, function($user) { return $user['role'] === 'Administrator'; })) }}</div>
                        <div>Administrator</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-shield fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Pengguna</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>#{{ str_pad($user['id'], 3, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    {{ strtoupper(substr($user['name'], 0, 1)) }}
                                </div>
                                <div>{{ $user['name'] }}</div>
                            </div>
                        </td>
                        <td>{{ $user['email'] }}</td>
                        <td>
                            @if($user['role'] === 'Administrator')
                                <span class="badge bg-danger">{{ $user['role'] }}</span>
                            @elseif($user['role'] === 'Manager')
                                <span class="badge bg-warning">{{ $user['role'] }}</span>
                            @else
                                <span class="badge bg-info">{{ $user['role'] }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $user['status'] === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $user['status'] }}
                            </span>
                        </td>
                        <td>{{ $user['created_at'] }}</td>
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
            <span class="text-muted">Menampilkan {{ count($users) }} dari {{ count($users) }} data</span>
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
