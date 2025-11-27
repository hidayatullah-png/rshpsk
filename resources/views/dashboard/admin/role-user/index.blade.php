@extends('layouts.admin.admin')

@section('title', 'Manajemen Role Pengguna')

@section('content')
<div class="main-container">
    <h2>Manajemen Role Pengguna</h2>

    {{-- HEADER ACTION: Tombol Tambah & Filter Toggle --}}
    <div class="action-header" style="justify-content: space-between; align-items: center;">
        
        {{-- Grup Tombol Filter --}}
        <div class="filter-buttons">
            @if($isShowingNonActive)
                {{-- Jika sedang lihat Nonaktif, tampilkan tombol kembali ke Aktif --}}
                <a href="{{ route('dashboard.admin.role-user.index') }}" class="btn btn-primary">
                    <i class="fas fa-eye"></i> Tampilkan Data Aktif
                </a>
                <span class="badge badge-danger" style="margin-left: 10px; font-size: 0.9rem;">
                    Mode: Nonaktif
                </span>
            @else
                {{-- Jika sedang lihat Aktif (Default), tampilkan tombol ke Nonaktif --}}
                <a href="{{ route('dashboard.admin.role-user.index', ['status' => 0]) }}" class="btn btn-secondary">
                    <i class="fas fa-archive"></i> Tampilkan Data Nonaktif
                </a>
            @endif
        </div>

        {{-- Tombol Tambah --}}
        <a href="{{ route('dashboard.admin.role-user.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Role User
        </a>
    </div>

    {{-- TABEL DATA --}}
    @if ($sortedRows->isNotEmpty())
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Pengguna</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop data yang sudah disortir dari Controller --}}
                    @foreach ($sortedRows as $row)
                        <tr>
                            {{-- Penomoran: 1, 2, 3 dst --}}
                            <td>{{ $loop->iteration }}</td>
                            
                            <td style="text-align: left;">{{ $row->user_name }}</td>
                            
                            <td>
                                {{ $row->role_name }}
                                @if($row->is_pemilik)
                                    <i style="color: gold; margin-left: 5px;" title="Pemilik"></i>
                                @endif
                            </td>
                            
                            <td>
                                @if ($row->status == 1)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>

                            <td class="action-buttons">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('dashboard.admin.role-user.edit', $row->id) }}"
                                   class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('dashboard.admin.role-user.destroy', $row->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus role \'{{ $row->role_name }}\' dari user \'{{ $row->user_name }}\'?')">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        {{-- Empty State --}}
        <div class="empty-message">
            <p>
                Tidak ada data dengan status 
                <strong>{{ $isShowingNonActive ? 'Nonaktif' : 'Aktif' }}</strong>.
            </p>
            @if($isShowingNonActive)
                <div class="empty-state-actions">
                    <a href="{{ route('dashboard.admin.role-user.index') }}" class="btn btn-primary">
                        Kembali ke Data Aktif
                    </a>
                </div>
            @else
                <div class="empty-state-actions">
                    <a href="{{ route('dashboard.admin.role-user.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Tambah Role User Pertama
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>

<style>
    .action-header {
        display: flex;
        justify-content: space-between; /* Membuat tombol kiri dan kanan berjauhan */
        flex-wrap: wrap;
        gap: 10px;
    }
    .badge {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
    }
    .badge-success { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
    .badge-danger { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }

    @media (max-width: 768px) {
        .action-header {
            flex-direction: column;
            align-items: stretch;
        }
        .action-header .btn {
            width: 100%;
            margin-bottom: 5px;
        }
    }
</style>
@endsection