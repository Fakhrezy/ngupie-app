@extends('layouts.app')

@section('title', 'Data Pelanggan - Coffee Shop Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-user-friends me-2"></i>Data Pelanggan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Pelanggan
        </button>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="h3 mb-0">{{ count($customers) }}</div>
                <div>Total Pelanggan</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="h3 mb-0">{{ count(array_filter($customers, function($c) { return $c['status'] === 'VIP'; })) }}</div>
                <div>Pelanggan VIP</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="h3 mb-0">{{ array_sum(array_column($customers, 'total_orders')) }}</div>
                <div>Total Pesanan</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Pelanggan</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Total Order</th>
                        <th>Terakhir Order</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td>#{{ str_pad($customer['id'], 3, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $customer['name'] }}</td>
                        <td>{{ $customer['email'] }}</td>
                        <td>{{ $customer['phone'] }}</td>
                        <td><span class="badge bg-primary">{{ $customer['total_orders'] }}</span></td>
                        <td>{{ $customer['last_order'] }}</td>
                        <td>
                            <span class="badge {{ $customer['status'] === 'VIP' ? 'bg-warning' : 'bg-success' }}">
                                {{ $customer['status'] }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
