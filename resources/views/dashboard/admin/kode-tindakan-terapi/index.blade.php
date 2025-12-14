@extends('layouts.admin.admin')

@section('title', 'Kode Tindakan Terapi')

@section('content')
<div class="main-container">
    <h2>Kode Tindakan Terapi</h2>

    <div class="action-header">
        {{-- Filter Button --}}
        <div class="filter-buttons">
            @if(request('trash') == 1)
                <a href="{{ route('dashboard.admin.kode-tindakan-terapi.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Data Aktif
                </a>
                <span class="badge badge-danger ml-2">
                    <i class="fas fa-trash"></i> Mode: Sampah
                </span>
            @else
                <a href="{{ route('dashboard.admin.kode-tindakan-terapi.index', ['trash' => 1]) }}" class="btn btn-secondary">
                    <i class="fas fa-trash-restore"></i> Lihat Data Terhapus
                </a>
            @endif
        </div>

        {{-- Add Button --}}
        @if(request('trash') != 1)
            <a href="{{ route('dashboard.admin.kode-tindakan-terapi.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Tambah Data
            </a>
        @endif
    </div>

    {{-- Table --}}
    @if ($KodeTindakanTerapi->isNotEmpty())
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode</th>
                        <th>Deskripsi</th>
                        <th>Kategori</th>
                        <th>Kategori Klinis</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($KodeTindakanTerapi as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight:bold;">{{ $item->kode }}</td>
                            <td style="text-align: left;">{{ $item->deskripsi_tindakan_terapi }}</td>
                            <td>{{ $item->nama_kategori }}</td>
                            <td>{{ $item->nama_kategori_klinis }}</td>
                            <td>
                                @if(request('trash') == 1)
                                    <span class="badge badge-danger">Terhapus</span>
                                    <br><small class="text-muted">{{ \Carbon\Carbon::parse($item->deleted_at)->format('d M Y') }}</small>
                                @else
                                    <span class="badge badge-success">Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                    @if(request('trash') == 1)
                                        {{-- RESTORE --}}
                                        <a href="{{ route('dashboard.admin.kode-tindakan-terapi.restore', $item->idkode_tindakan_terapi) }}" 
                                           class="btn btn-info btn-sm"
                                           onclick="return confirm('Pulihkan data ini?')">
                                            <i class="fas fa-trash-restore"></i> Pulihkan
                                        </a>
                                    @else
                                        {{-- EDIT --}}
                                        <a href="{{ route('dashboard.admin.kode-tindakan-terapi.edit', $item->idkode_tindakan_terapi) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        {{-- DELETE --}}
                                        <form action="{{ route('dashboard.admin.kode-tindakan-terapi.destroy', $item->idkode_tindakan_terapi) }}" method="POST">
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