@extends('layouts.admin.admin')

@section('title', 'Manajemen Ras Hewan')

@section('content')
<div class="main-container">
    <h2>Manajemen Ras Hewan</h2>

    {{-- Tombol Tambah --}}
    <div class="action-header">
        <a href="{{ route('dashboard.admin.ras-hewan.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Ras Hewan
        </a>
    </div>

    {{-- Tabel Data --}}
    @if ($rasList->isNotEmpty())
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Ras</th>
                        <th>Jenis Hewan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rasList as $ras)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ras->nama_ras }}</td>
                            
                            {{-- 
                                Catatan: Pastikan Controller kamu melakukan JOIN tabel 
                                atau menggunakan relasi agar 'nama_jenis_hewan' muncul.
                                Saya tambahkan '??' agar tidak error jika datanya null.
                            --}}
                            <td>{{ $ras->nama_jenis_hewan ?? '-' }}</td>
                            
                            <td class="action-buttons">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('dashboard.admin.ras-hewan.edit', $ras->idras_hewan) }}"
                                    class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('dashboard.admin.ras-hewan.destroy', $ras->idras_hewan) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus ras {{ $ras->nama_ras }}?')">
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
            <p>Tidak ada data ras hewan yang tersedia.</p>
            <div class="empty-state-actions">
                <a href="{{ route('dashboard.admin.ras-hewan.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Tambah Ras Hewan Pertama
                </a>
            </div>
        </div>
    @endif
</div>
@endsection