@extends('layouts.admin.admin')

@section('title', 'Manajemen Ras Hewan')

@section('content')
<div class="main-container">
    <h2>Manajemen Ras Hewan</h2>

    <div class="action-header">
        <div class="filter-buttons">
            @if(request('trash') == 1)
                <a href="{{ route('dashboard.admin.ras-hewan.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Data Aktif
                </a>
                <span class="badge badge-danger ml-2">
                    <i class="fas fa-trash"></i> Mode: Sampah
                </span>
            @else
                <a href="{{ route('dashboard.admin.ras-hewan.index', ['trash' => 1]) }}" class="btn btn-secondary">
                    <i class="fas fa-trash-restore"></i> Lihat Data Terhapus
                </a>
            @endif
        </div>

        @if(request('trash') != 1)
            <a href="{{ route('dashboard.admin.ras-hewan.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Tambah Ras Baru
            </a>
        @endif
    </div>

    @if ($rasList->isNotEmpty())
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Jenis Hewan</th>
                        <th>Nama Ras</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rasList as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_jenis_hewan }}</td>
                            <td style="font-weight: bold;">{{ $item->nama_ras }}</td>
                            
                            <td>
                                @if (request('trash') == 1)
                                    <span class="badge badge-danger">Terhapus</span>
                                @else
                                    <span class="badge badge-success">Aktif</span>
                                @endif
                            </td>

                            <td>
                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                    @if(request('trash') == 1)
                                        {{-- RESTORE --}}
                                        <a href="{{ route('dashboard.admin.ras-hewan.restore', $item->idras_hewan) }}" 
                                           class="btn btn-info btn-sm"
                                           onclick="return confirm('Pulihkan ras hewan ini?')">
                                            <i class="fas fa-trash-restore"></i> Pulihkan
                                        </a>
                                    @else
                                        {{-- EDIT --}}
                                        <a href="{{ route('dashboard.admin.ras-hewan.edit', $item->idras_hewan) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        {{-- DELETE --}}
                                        <form action="{{ route('dashboard.admin.ras-hewan.destroy', $item->idras_hewan) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-warning btn-sm"
                                                    onclick="return confirm('Pindahkan ke sampah?')">
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
        <div class="empty-message">
            <p>Tidak ada data Ras Hewan <strong>{{ request('trash') == 1 ? 'di Sampah' : 'Aktif' }}</strong>.</p>
        </div>
    @endif
</div>

@endsection