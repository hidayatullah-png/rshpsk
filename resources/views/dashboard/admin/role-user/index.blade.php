@extends('layouts.admin.admin')

@section('title', 'Manajemen Role Pengguna')

@section('content')
<div class="main-container">
    <h2>Manajemen Role Pengguna</h2>

    {{-- HEADER ACTION: Tombol Tambah & Filter Toggle --}}
    <div class="action-header">
        
        {{-- Grup Tombol Filter --}}
        <div class="filter-buttons">
            {{-- Cek apakah sedang di Mode Sampah (trash=1) --}}
            @if(request('trash') == 1)
                <a href="{{ route('dashboard.admin.role-user.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Data Aktif
                </a>
                <span class="badge badge-danger ml-2">
                    <i class="fas fa-trash"></i> Mode: Sampah (Terhapus)
                </span>
            @else
                {{-- Jika di Mode Aktif, tampilkan tombol ke Sampah --}}
                {{-- PERBAIKAN: Link mengarah ke trash=1 agar data soft delete (Enkrid) muncul --}}
                <a href="{{ route('dashboard.admin.role-user.index', ['trash' => 1]) }}" class="btn btn-secondary">
                    <i class="fas fa-trash-restore"></i> Lihat Data Terhapus
                </a>
            @endif
        </div>

        {{-- Tombol Tambah (Hanya muncul jika BUKAN di mode sampah) --}}
        @if(request('trash') != 1)
            <a href="{{ route('dashboard.admin.role-user.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Tambah Role User
            </a>
        @endif
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
                        <th style="min-width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sortedRows as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            
                            <td style="text-align: left;">{{ $row->user_name }}</td>
                            
                            <td>
                                {{ $row->role_name }}
                                @if($row->is_pemilik)
                                    <i class="fas fa-crown text-warning ml-1" title="Pemilik"></i>
                                @endif
                            </td>
                            
                            <td>
                                @if (request('trash') == 1)
                                    <span class="badge badge-danger">Terhapus</span>
                                @elseif ($row->status == 1)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Nonaktif</span>
                                @endif
                            </td>

                            <td>
                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                    @if(request('trash') == 1)
                                        {{-- === MODE SAMPAH (RESTORE & FORCE DELETE) === --}}
                                        
                                        {{-- Tombol Restore (Gunakan ID Pivot role_user) --}}
                                        <a href="{{ url('dashboard/admin/role-user/'.$row->id.'/restore') }}" 
                                           class="btn btn-info btn-sm"
                                           onclick="return confirm('Pulihkan user ini agar bisa login kembali?')">
                                            <i class="fas fa-trash-restore"></i> Pulihkan
                                        </a>

                                        {{-- Tombol Force Delete --}}
                                        <form action="{{ route('dashboard.admin.role-user.destroy', $row->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('PERINGATAN: User akan dihapus PERMANEN dan tidak bisa dikembalikan. Lanjutkan?')">
                                                <i class="fas fa-times"></i> Hapus Permanen
                                            </button>
                                        </form>

                                    @else
                                        {{-- === MODE AKTIF (EDIT & SOFT DELETE) === --}}
                                        
                                        <a href="{{ route('dashboard.admin.role-user.edit', $row->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <form action="{{ route('dashboard.admin.role-user.destroy', $row->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-warning btn-sm"
                                                    onclick="return confirm('Pindahkan user ini ke sampah? User tidak akan bisa login.')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
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
                Tidak ada data 
                <strong>{{ request('trash') == 1 ? 'di Sampah' : 'Aktif' }}</strong>.
            </p>
            @if(request('trash') == 1)
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

@endsection