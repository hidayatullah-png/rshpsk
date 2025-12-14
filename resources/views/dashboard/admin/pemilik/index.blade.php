@extends('layouts.admin.admin')

@section('title', 'Manajemen Pemilik')

@section('content')
<div class="main-container">
    <h2>Manajemen Pemilik</h2>

    {{-- HEADER ACTION --}}
    <div class="action-header">
        <div class="filter-buttons">
            @if(request('trash') == 1)
                <a href="{{ route('dashboard.admin.pemilik.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Data Aktif
                </a>
                <span class="badge badge-danger ml-2">
                    <i class="fas fa-trash"></i> Mode: Sampah
                </span>
            @else
                <a href="{{ route('dashboard.admin.pemilik.index', ['trash' => 1]) }}" class="btn btn-secondary">
                    <i class="fas fa-trash-restore"></i> Lihat Data Terhapus
                </a>
            @endif
        </div>

        @if(request('trash') != 1)
            <a href="{{ route('dashboard.admin.pemilik.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Tambah Pemilik Baru
            </a>
        @endif
    </div>

    {{-- TABLE --}}
    @if ($pemilikList->isNotEmpty())
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Pemilik (User)</th>
                        <th>Email</th>
                        <th>No. WA</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemilikList as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight: bold;">{{ $item->nama }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->no_wa }}</td>
                            <td>{{ $item->alamat }}</td>
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
                                        <a href="{{ route('dashboard.admin.pemilik.restore', $item->idpemilik) }}" 
                                           class="btn btn-info btn-sm"
                                           onclick="return confirm('Pulihkan data pemilik ini?')">
                                            <i class="fas fa-trash-restore"></i> Pulihkan
                                        </a>
                                    @else
                                        {{-- EDIT --}}
                                        <a href="{{ route('dashboard.admin.pemilik.edit', $item->idpemilik) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        {{-- DELETE --}}
                                        <form action="{{ route('dashboard.admin.pemilik.destroy', $item->idpemilik) }}" method="POST">
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
            <p>Tidak ada data Pemilik <strong>{{ request('trash') == 1 ? 'di Sampah' : 'Aktif' }}</strong>.</p>
        </div>
    @endif
</div>

@endsection