@extends('layouts.app')

@section('title', 'Data Pesanan - Coffee Shop Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-shopping-cart me-2"></i>Data Pesanan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Pesanan Baru
        </button>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="h3 mb-0">{{ count($orders) }}</div>
                <div>Total Pesanan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="h3 mb-0">{{ count(array_filter($orders, function($o) { return $o['status'] === 'Completed'; })) }}</div>
                <div>Selesai</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="h3 mb-0">{{ count(array_filter($orders, function($o) { return $o['status'] === 'In Progress'; })) }}</div>
                <div>Dalam Proses</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="h6 mb-0">Rp {{ number_format(array_sum(array_column($orders, 'total'))) }}</div>
                <div>Total Pendapatan</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Pesanan</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Metode Bayar</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>#{{ str_pad($order['id'], 3, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ $order['customer'] }}</strong></td>
                        <td>{{ Str::limit($order['items'], 30) }}</td>
                        <td><strong>Rp {{ number_format($order['total']) }}</strong></td>
                        <td>
                            @if($order['status'] === 'Completed')
                                <span class="badge bg-success">{{ $order['status'] }}</span>
                            @elseif($order['status'] === 'In Progress')
                                <span class="badge bg-warning">{{ $order['status'] }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $order['status'] }}</span>
                            @endif
                        </td>
                        <td>{{ $order['payment_method'] }}</td>
                        <td>{{ $order['date'] }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-info"><i class="fas fa-print"></i></button>
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
