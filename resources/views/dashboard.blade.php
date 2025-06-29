@extends('layouts.app')

@section('title', 'Dashboard - Coffee Shop Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
        <button type="button" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Data
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h4 mb-0">{{ $stats['total_users'] }}</div>
                        <div class="small">Total Pengguna</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="card stat-card-2 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h4 mb-0">{{ $stats['total_employees'] }}</div>
                        <div class="small">Total Karyawan</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-tie fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="card stat-card-3 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h4 mb-0">{{ $stats['total_customers'] }}</div>
                        <div class="small">Total Pelanggan</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-friends fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="card stat-card-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h4 mb-0">{{ $stats['total_menus'] }}</div>
                        <div class="small">Total Menu</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book-open fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="card stat-card-5 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h4 mb-0">{{ $stats['total_orders'] }}</div>
                        <div class="small">Total Pesanan</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 mb-3">
        <div class="card stat-card-6 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h4 mb-0">Rp {{ number_format($stats['today_revenue']) }}</div>
                        <div class="small">Pendapatan Hari Ini</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables -->
<div class="row">
    <!-- Data Pengguna -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Data Pengguna</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user['name'] }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $user['role'] }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $user['status'] === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $user['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Karyawan -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Data Karyawan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Posisi</th>
                                <th>Departemen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                            <tr>
                                <td>{{ $employee['name'] }}</td>
                                <td>{{ $employee['position'] }}</td>
                                <td>{{ $employee['department'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Menu -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-book-open me-2"></i>Data Menu</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Menu</th>
                                <th>Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                            <tr>
                                <td>{{ $menu['name'] }}</td>
                                <td>Rp {{ number_format($menu['price']) }}</td>
                                <td>
                                    <span class="badge {{ $menu['status'] === 'Available' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $menu['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Pesanan -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Data Pesanan Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order['customer'] }}</td>
                                <td>Rp {{ number_format($order['total']) }}</td>
                                <td>
                                    @if($order['status'] === 'Completed')
                                        <span class="badge bg-success">{{ $order['status'] }}</span>
                                    @elseif($order['status'] === 'In Progress')
                                        <span class="badge bg-warning">{{ $order['status'] }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $order['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .stat-card-2 {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    .stat-card-3 {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }
    .stat-card-4 {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }
    .stat-card-5 {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }
    .stat-card-6 {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #333;
    }
</style>
@endsection
