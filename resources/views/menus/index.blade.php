@extends('layouts.app')

@section('title', 'Data Menu - Coffee Shop Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-book-open me-2"></i>Data Menu</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Menu
        </button>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="h3 mb-0">{{ count($menus) }}</div>
                <div>Total Menu</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="h3 mb-0">{{ count(array_filter($menus, function($m) { return $m['status'] === 'Available'; })) }}</div>
                <div>Menu Tersedia</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="h3 mb-0">{{ count(array_filter($menus, function($m) { return $m['status'] === 'Out of Stock'; })) }}</div>
                <div>Stok Habis</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="h6 mb-0">Rp {{ number_format(array_sum(array_column($menus, 'price')) / count($menus)) }}</div>
                <div>Harga Rata-rata</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Menu</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                    <tr>
                        <td>#{{ str_pad($menu['id'], 3, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ $menu['name'] }}</strong></td>
                        <td>
                            <span class="badge bg-secondary">{{ $menu['category'] }}</span>
                        </td>
                        <td><strong>Rp {{ number_format($menu['price']) }}</strong></td>
                        <td>{{ Str::limit($menu['description'], 50) }}</td>
                        <td>
                            <span class="badge {{ $menu['status'] === 'Available' ? 'bg-success' : 'bg-danger' }}">
                                {{ $menu['status'] }}
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
