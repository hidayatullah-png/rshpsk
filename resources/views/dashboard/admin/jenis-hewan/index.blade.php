@extends('layouts.admin.admin')

@section('title', 'Manajemen Jenis Hewan')

@section('content')
    <div class="main-container">
        <h2>Manajemen Jenis Hewan</h2>

        <div class="action-header">
            {{-- KIRI --}}
            <div class="action-left">
                @if(request('trash') == 1)
                    <a href="{{ route('dashboard.admin.jenis-hewan.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Data Aktif
                    </a>
                    <span class="badge badge-danger ml-2">
                        <i class="fas fa-trash"></i> Mode: Sampah
                    </span>
                @else
                    <a href="{{ route('dashboard.admin.jenis-hewan.index', ['trash' => 1]) }}" class="btn btn-secondary">
                        <i class="fas fa-trash-restore"></i> Lihat Data Terhapus
                    </a>
                @endif
            </div>

            {{-- KANAN --}}
            @if(request('trash') != 1)
                <a href="{{ route('dashboard.admin.jenis-hewan.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Tambah Jenis
                </a>
            @endif
        </div>


        @if ($list->isNotEmpty())
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Jenis Hewan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="text-align: left;">{{ $item->nama_jenis_hewan }}</td>
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
                                            {{-- RESTORE BUTTON --}}
                                            <a href="{{ route('dashboard.admin.jenis-hewan.restore', $item->idjenis_hewan) }}"
                                                class="btn btn-info btn-sm" onclick="return confirm('Pulihkan jenis hewan ini?')">
                                                <i class="fas fa-trash-restore"></i> Pulihkan
                                            </a>
                                        @else
                                            {{-- EDIT BUTTON --}}
                                            <a href="{{ route('dashboard.admin.jenis-hewan.edit', $item->idjenis_hewan) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            {{-- DELETE BUTTON --}}
                                            <form action="{{ route('dashboard.admin.jenis-hewan.destroy', $item->idjenis_hewan) }}"
                                                method="POST">
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