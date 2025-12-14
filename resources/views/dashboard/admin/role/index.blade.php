@extends('layouts.admin.admin')

@section('title', 'Manajemen Role')

@section('content')
<div class="main-container">
    <h2>Manajemen Role</h2>

    {{-- HEADER ACTION --}}
    <div class="action-header">
        
        {{-- Grup Tombol Filter --}}
        <div class="filter-buttons">
            @if(request('trash') == 1)
                {{-- Tombol Balik ke Aktif --}}
                <a href="{{ route('dashboard.admin.role.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Data Aktif
                </a>
                <span class="badge badge-danger ml-2">
                    <i class="fas fa-trash"></i> Mode: Sampah (Terhapus)
                </span>
            @else
                {{-- Tombol Lihat Sampah --}}
                <a href="{{ route('dashboard.admin.role.index', ['trash' => 1]) }}" class="btn btn-secondary">
                    <i class="fas fa-trash-restore"></i> Lihat Data Terhapus
                </a>
            @endif
        </div>

        {{-- Tombol Tambah (Hanya di mode aktif) --}}
        @if(request('trash') != 1)
            <a href="{{ route('dashboard.admin.role.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Tambah Role
            </a>
        @endif
    </div>

    {{-- TABEL DATA --}}
    @if ($role->isNotEmpty())
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Role</th>
                        <th>Status</th>
                        <th style="min-width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($role as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            
                            <td style="text-align: left;">
                                <strong>{{ $item->nama_role }}</strong>
                            </td>
                            
                            <td>
                                @if (request('trash') == 1)
                                    <span class="badge badge-danger">Terhapus</span>
                                    <br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($item->deleted_at)->format('d M Y') }}
                                    </small>
                                @else
                                    <span class="badge badge-success">Aktif</span>
                                @endif
                            </td>

                            <td>
                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                    @if(request('trash') == 1)
                                        
                                        {{-- === MODE SAMPAH (HANYA RESTORE) === --}}
                                        <a href="{{ route('dashboard.admin.role.restore', $item->idrole) }}" 
                                           class="btn btn-info btn-sm"
                                           onclick="return confirm('Pulihkan role ini?')">
                                            <i class="fas fa-trash-restore"></i> Pulihkan
                                        </a>

                                    @else
                                        
                                        {{-- === MODE AKTIF (EDIT & SOFT DELETE) === --}}
                                        <a href="{{ route('dashboard.admin.role.edit', $item->idrole) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <form action="{{ route('dashboard.admin.role.destroy', $item->idrole) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-warning btn-sm"
                                                    onclick="return confirm('Pindahkan role ke sampah?')">
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
                Tidak ada data Role 
                <strong>{{ request('trash') == 1 ? 'di Sampah' : 'Aktif' }}</strong>.
            </p>
            @if(request('trash') == 1)
                <div class="empty-state-actions">
                    <a href="{{ route('dashboard.admin.role.index') }}" class="btn btn-primary">
                        Kembali ke Data Aktif
                    </a>
                </div>
            @else
                <div class="empty-state-actions">
                    <a href="{{ route('dashboard.admin.role.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Tambah Role Pertama
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection