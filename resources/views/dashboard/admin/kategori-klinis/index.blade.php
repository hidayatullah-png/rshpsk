@extends('layouts.admin.admin')

@section('title', 'Manajemen Kategori Klinis')

@section('content')
<div class="main-container">
    <h2>Manajemen Kategori Klinis</h2>

    <div class="action-header">
        {{-- Filter Mode --}}
        <div class="filter-buttons">
            @if(request('trash') == 1)
                <a href="{{ route('dashboard.admin.kategori-klinis.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Data Aktif
                </a>
                <span class="badge badge-danger ml-2">
                    <i class="fas fa-trash"></i> Mode: Sampah
                </span>
            @else
                <a href="{{ route('dashboard.admin.kategori-klinis.index', ['trash' => 1]) }}" class="btn btn-secondary">
                    <i class="fas fa-trash-restore"></i> Lihat Data Terhapus
                </a>
            @endif
        </div>

        {{-- Tombol Tambah --}}
        @if(request('trash') != 1)
            <a href="{{ route('dashboard.admin.kategori-klinis.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Tambah Kategori
            </a>
        @endif
    </div>

    {{-- Tabel --}}
    @if ($kategoriKlinis->isNotEmpty())
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="50">No.</th>
                        <th>Nama Kategori Klinis</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategoriKlinis as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="text-align: left; font-weight: bold;">{{ $item->nama_kategori_klinis }}</td>
                            <td>
                                @if(request('trash') == 1)
                                    <span class="badge badge-danger">Terhapus</span>
                                    <br> <small class="text-muted">{{ \Carbon\Carbon::parse($item->deleted_at)->format('d M Y') }}</small>
                                @else
                                    <span class="badge badge-success">Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                    @if(request('trash') == 1)
                                        {{-- RESTORE --}}
                                        <a href="{{ route('dashboard.admin.kategori-klinis.restore', $item->idkategori_klinis) }}" 
                                           class="btn btn-info btn-sm"
                                           onclick="return confirm('Pulihkan kategori ini?')">
                                            <i class="fas fa-trash-restore"></i> Pulihkan
                                        </a>
                                    @else
                                        {{-- EDIT --}}
                                        <a href="{{ route('dashboard.admin.kategori-klinis.edit', $item->idkategori_klinis) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        {{-- DELETE --}}
                                        <form action="{{ route('dashboard.admin.kategori-klinis.destroy', $item->idkategori_klinis) }}" method="POST">
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
            <p>Tidak ada data <strong>{{ request('trash') == 1 ? 'di Sampah' : 'Aktif' }}</strong>.</p>
        </div>
    @endif
</div>

@endsection